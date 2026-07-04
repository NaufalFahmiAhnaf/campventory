<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik (Halaman Welcome)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rute Yang Memerlukan Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Master Data Kategori (Admin & Staff) ---
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

    // --- Master Data Barang/Produk (Admin & Staff) ---
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // --- Transaksi Peminjaman (Admin & Staff) ---
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBorrowing'])->name('borrowings.return');
    });

    // --- Laporan & Ekspor Data (Admin, Staff, Manager) ---
    Route::middleware('role:admin,staff,manager')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/products/pdf', [ReportController::class, 'exportProductsPdf'])->name('reports.products.pdf');
        Route::get('/reports/products/excel', [ReportController::class, 'exportProductsExcel'])->name('reports.products.excel');
        Route::get('/reports/borrowings/pdf', [ReportController::class, 'exportBorrowingsPdf'])->name('reports.borrowings.pdf');
        Route::get('/reports/borrowings/excel', [ReportController::class, 'exportBorrowingsExcel'])->name('reports.borrowings.excel');
    });

    // --- Kelola User (Admin) ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', function () {
            return response('Fitur Kelola User dalam pengembangan.', 200);
        })->name('users.index');
    });
});

require __DIR__.'/auth.php';
