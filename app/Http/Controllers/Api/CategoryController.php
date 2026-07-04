<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh kategori barang.
     * 
     * GET /api/categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }

    /**
     * Menampilkan detail 1 kategori beserta produk-produknya.
     * 
     * GET /api/categories/{id}
     */
    public function show(Category $category)
    {
        $category->load('products');

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
    }

    /**
     * Menyimpan kategori baru.
     * 
     * POST /api/categories
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $category,
        ], 201);
    }

    /**
     * Memperbarui kategori.
     * 
     * PUT /api/categories/{id}
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category,
        ], 200);
    }

    /**
     * Menghapus kategori.
     * 
     * DELETE /api/categories/{id}
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki barang terdaftar.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ], 200);
    }
}
