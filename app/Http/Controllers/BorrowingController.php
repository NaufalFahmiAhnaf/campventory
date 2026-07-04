<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    /**
     * Tampilkan daftar transaksi peminjaman.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['processedBy', 'details.product.category']);

        // Cari berdasarkan nama peminjam
        if ($request->has('search') && $request->search != '') {
            $query->where('borrower_name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status tampilan, termasuk "Terlambat" yang dihitung dari tanggal jatuh tempo.
        $query->filterByDisplayStatus($request->input('status'));

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Tampilkan form pembuatan peminjaman baru.
     */
    public function create()
    {
        // Ambil barang yang memiliki stok > 0 dan tidak rusak berat
        $products = Product::where('stock', '>', 0)
            ->where('condition', '!=', 'Rusak Berat')
            ->orderBy('name', 'asc')
            ->get();

        return view('borrowings.create', compact('products'));
    }

    /**
     * Simpan transaksi peminjaman baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:borrow_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'expected_return_date.required' => 'Batas tanggal pengembalian wajib diisi.',
            'expected_return_date.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
            'items.required' => 'Anda harus memilih minimal 1 barang untuk dipinjam.',
        ]);

        DB::beginTransaction();

        try {
            // Buat record Peminjaman
            $borrowing = Borrowing::create([
                'borrower_name' => $request->borrower_name,
                'borrow_date' => $request->borrow_date,
                'expected_return_date' => $request->expected_return_date,
                'status' => 'Dipinjam',
                'processed_by' => Auth::id(),
            ]);

            // Buat record detail & kurangi stok produk
            foreach ($request->items as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();

                // Validasi kecukupan stok saat transaksi diproses
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Stok untuk barang "' . $product->name . '" tidak mencukupi. Tersedia: ' . $product->stock . ' unit.');
                }

                // Buat detail peminjaman
                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'returned_at' => null
                ]);

                // Kurangi stok barang
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('borrowings.index')->with('success', 'Transaksi peminjaman berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail transaksi peminjaman.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['processedBy', 'details.product.category']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Proses pengembalian barang peminjaman.
     */
    public function returnBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->status === 'Dikembalikan') {
            return redirect()->back()->with('error', 'Transaksi peminjaman ini sudah dikembalikan sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $borrowing->load('details.product');

            // Perbarui status peminjaman
            $borrowing->update([
                'status' => 'Dikembalikan'
            ]);

            // Perbarui tanggal dikembalikan dan kembalikan stok barang
            foreach ($borrowing->details as $detail) {
                if ($detail->returned_at) {
                    continue;
                }

                $detail->update([
                    'returned_at' => now()
                ]);

                // Kembalikan stok barang
                $detail->product->increment('stock', $detail->quantity);
            }

            DB::commit();
            return redirect()->route('borrowings.index')->with('success', 'Pengembalian barang berhasil dicatat. Stok barang telah dikembalikan ke dalam gudang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pengembalian barang: ' . $e->getMessage());
        }
    }
}
