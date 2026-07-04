<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BorrowingController;

/*
|--------------------------------------------------------------------------
| API Routes - CampVentory REST API
|--------------------------------------------------------------------------
|
| Semua rute API diawali dengan prefix /api
| Autentikasi menggunakan Laravel Sanctum (Bearer Token)
|
| Cara penggunaan:
| 1. POST /api/login -> Dapatkan token
| 2. Sertakan header: Authorization: Bearer {token}
| 3. Akses endpoint yang tersedia di bawah ini
|
*/

// ============================
// Rute Publik (Tanpa Token)
// ============================
Route::post('/login', [AuthController::class, 'login']);

// ============================
// Rute Terautentikasi (Butuh Bearer Token)
// ============================
Route::middleware('auth:sanctum')->group(function () {
    
    // --- Auth ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // --- Kategori, Produk, Peminjaman (Dalam Namespace Rute API) ---
    Route::name('api.')->group(function () {
        // --- Kategori ---
        Route::apiResource('categories', CategoryController::class);

        // --- Produk / Barang ---
        Route::apiResource('products', ProductController::class);

        // --- Peminjaman ---
        Route::apiResource('borrowings', BorrowingController::class)->only(['index', 'show', 'store']);
    });
    
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBorrowing'])->name('api.borrowings.return');
});
