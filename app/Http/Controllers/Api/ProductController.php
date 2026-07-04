<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar seluruh barang inventaris.
     * 
     * GET /api/products
     * Query Params: ?search=keyword&category_id=1
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('name', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
        ], 200);
    }

    /**
     * Menampilkan detail 1 barang berdasarkan ID.
     * 
     * GET /api/products/{id}
     */
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }

    /**
     * Menyimpan barang baru ke database.
     * 
     * POST /api/products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:products,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'storage_location' => 'required|string|max:255',
            'condition' => 'required|in:Baik,Kurang Baik,Rusak Berat',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
            'data' => $product->load('category'),
        ], 201);
    }

    /**
     * Memperbarui data barang yang sudah ada.
     * 
     * PUT /api/products/{id}
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'storage_location' => 'required|string|max:255',
            'condition' => 'required|in:Baik,Kurang Baik,Rusak Berat',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui.',
            'data' => $product->load('category'),
        ], 200);
    }

    /**
     * Menghapus barang dari database.
     * 
     * DELETE /api/products/{id}
     */
    public function destroy(Product $product)
    {
        // Cek apakah produk sedang dipinjam
        $activeBorrowing = $product->borrowingDetails()
            ->whereHas('borrowing', fn ($q) => $q->where('status', 'Dipinjam'))
            ->exists();

        if ($activeBorrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak dapat dihapus karena masih ada peminjaman aktif.',
            ], 422);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus.',
        ], 200);
    }
}
