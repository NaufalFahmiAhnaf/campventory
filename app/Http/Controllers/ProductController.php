<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar barang dengan pencarian, pemfilteran, dan pagination.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Pencarian berdasarkan nama barang atau kode barang
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('storage_location', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan kondisi
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition', $request->condition);
        }

        // Filter stok tipis (kurang dari 5)
        if ($request->has('filter_stock') && $request->filter_stock === 'low') {
            $query->where('stock', '<', 5);
        }

        $products = $query->orderBy('code', 'asc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:50|unique:products,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'storage_location' => 'required|string|max:255',
            'condition' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'storage_location.required' => 'Lokasi penyimpanan wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg, webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->except('image');

        // Jika kode barang tidak diisi, generate otomatis berdasarkan kategori
        if (!$request->filled('code')) {
            $data['code'] = $this->generateProductCode($request->category_id);
        } else {
            $data['code'] = strtoupper($request->code);
        }

        // Penanganan upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        $product = Product::create($data);
        \App\Models\ActivityLog::log('Tambah Barang', 'Barang "' . $product->name . '" (Kode: ' . $product->code . ', Stok: ' . $product->stock . ') berhasil ditambahkan.');

        return redirect()->route('products.index')->with('success', 'Barang "' . $request->name . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail barang.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'borrowingDetails.borrowing']);
        return view('products.show', compact('product'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui data barang di database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'storage_location' => 'required|string|max:255',
            'condition' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'code.required' => 'Kode barang wajib diisi.',
            'code.unique' => 'Kode barang sudah digunakan oleh barang lain.',
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'storage_location.required' => 'Lokasi penyimpanan wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg, webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->except('image');
        $data['code'] = strtoupper($request->code);

        // Penanganan edit upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        $product->update($data);
        \App\Models\ActivityLog::log('Ubah Barang', 'Data barang "' . $product->name . '" (Kode: ' . $product->code . ') berhasil diperbarui.');

        return redirect()->route('products.index')->with('success', 'Barang "' . $request->name . '" berhasil diperbarui.');
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy(Product $product)
    {
        // Cegah penghapusan jika barang sedang dipinjam
        $isBorrowed = $product->borrowingDetails()
            ->whereHas('borrowing', function($query) {
                $query->where('status', 'Dipinjam');
            })
            ->whereNull('returned_at')
            ->exists();

        if ($isBorrowed) {
            return redirect()->route('products.index')->with('error', 'Barang "' . $product->name . '" tidak dapat diarsipkan karena saat ini sedang dipinjam.');
        }

        $product->delete();

        \App\Models\ActivityLog::log('Arsipkan Barang', 'Barang "' . $product->name . '" (Kode: ' . $product->code . ') telah diarsipkan (Soft Delete).');

        return redirect()->route('products.index')->with('success', 'Barang berhasil diarsipkan. Riwayat transaksi masa lalu tetap aman terjaga.');
    }

    /**
     * Hapus barang secara permanen dari database.
     */
    public function forceDestroy($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        // 1. Cegah penghapusan permanen jika barang memiliki riwayat peminjaman di masa lalu
        $hasHistory = $product->borrowingDetails()->exists();
        if ($hasHistory) {
            return redirect()->route('products.index')->with('error', 'Barang "' . $product->name . '" memiliki riwayat transaksi peminjaman di masa lalu. Barang ini tidak bisa dihapus secara permanen agar integritas laporan sejarah tetap aman. Gunakan tombol "Hapus (Arsipkan)" saja.');
        }

        // 2. Cegah penghapusan jika barang sedang aktif dipinjam
        $isBorrowed = $product->borrowingDetails()
            ->whereHas('borrowing', function($query) {
                $query->where('status', 'Dipinjam');
            })
            ->whereNull('returned_at')
            ->exists();

        if ($isBorrowed) {
            return redirect()->route('products.index')->with('error', 'Barang "' . $product->name . '" tidak dapat dihapus permanen karena saat ini sedang dipinjam.');
        }

        // Hapus file gambar dari storage jika ada
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $name = $product->name;
        $code = $product->code;
        
        $product->forceDelete();

        \App\Models\ActivityLog::log('Hapus Barang Permanen', 'Barang "' . $name . '" (Kode: ' . $code . ') telah dihapus secara permanen dari database.');

        return redirect()->route('products.index')->with('success', 'Barang "' . $name . '" berhasil dihapus secara permanen dari sistem.');
    }

    /**
     * Generate kode barang otomatis berdasarkan inisial kategori.
     */
    private function generateProductCode($categoryId)
    {
        $category = Category::find($categoryId);
        $prefix = 'TSEL-';
        
        if ($category) {
            // Bersihkan nama kategori dari karakter non-huruf, lalu ambil 3 huruf pertama
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $category->name);
            $initials = strtoupper(substr($cleanName, 0, 3));
            if (strlen($initials) < 3) {
                $initials = str_pad($initials, 3, 'X');
            }
            $prefix .= $initials . '-';
        } else {
            $prefix .= 'BRG-';
        }

        // Hitung total produk di kategori tersebut untuk nomor urut
        $count = Product::where('category_id', $categoryId)->count() + 1;
        $code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Jika kode bentrok, terus iterasi sampai mendapatkan kode unik
        while (Product::where('code', $code)->exists()) {
            $count++;
            $code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}
