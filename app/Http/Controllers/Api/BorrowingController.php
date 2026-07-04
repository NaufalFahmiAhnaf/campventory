<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar seluruh transaksi peminjaman.
     * 
     * GET /api/borrowings
     * Query Params: ?status=Dipinjam&search=nama
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['processedBy', 'details.product']);

        if ($request->has('search') && $request->search != '') {
            $query->where('borrower_name', 'like', '%' . $request->search . '%');
        }

        $query->filterByDisplayStatus($request->input('status'));

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $borrowings,
        ], 200);
    }

    /**
     * Menampilkan detail 1 transaksi peminjaman.
     * 
     * GET /api/borrowings/{id}
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['processedBy', 'details.product.category']);

        return response()->json([
            'success' => true,
            'data' => $borrowing,
        ], 200);
    }

    /**
     * Membuat transaksi peminjaman baru.
     * 
     * POST /api/borrowings
     * Body: {
     *   "borrower_name": "...",
     *   "borrow_date": "2025-01-01",
     *   "expected_return_date": "2025-01-15",
     *   "items": [
     *     { "product_id": 1, "quantity": 2 },
     *     { "product_id": 3, "quantity": 1 }
     *   ]
     * }
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
        ]);

        DB::beginTransaction();

        try {
            $borrowing = Borrowing::create([
                'borrower_name' => $request->borrower_name,
                'borrow_date' => $request->borrow_date,
                'expected_return_date' => $request->expected_return_date,
                'status' => 'Dipinjam',
                'processed_by' => $request->user()->id,
            ]);

            foreach ($request->items as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok barang "' . $product->name . '" tidak mencukupi. Tersedia: ' . $product->stock . ' unit.',
                    ], 422);
                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'returned_at' => null,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi peminjaman berhasil dibuat.',
                'data' => $borrowing->load(['processedBy', 'details.product']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proses pengembalian semua barang dalam transaksi peminjaman.
     * 
     * POST /api/borrowings/{id}/return
     */
    public function returnBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->status === 'Dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ini sudah dikembalikan sebelumnya.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $borrowing->load('details.product');

            $borrowing->update(['status' => 'Dikembalikan']);

            foreach ($borrowing->details as $detail) {
                if ($detail->returned_at) {
                    continue;
                }

                $detail->update(['returned_at' => now()]);
                $detail->product->increment('stock', $detail->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil diproses. Stok barang telah dikembalikan.',
                'data' => $borrowing->fresh()->load(['processedBy', 'details.product']),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengembalian: ' . $e->getMessage(),
            ], 500);
        }
    }
}
