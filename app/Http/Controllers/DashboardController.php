<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index()
    {
        // 1. Total Stok yang saat ini ada di gudang (Tersedia)
        $availableStock = Product::sum('stock');

        // 2. Total Barang yang sedang dipinjam (Status Dipinjam)
        $totalItemsBorrowed = BorrowingDetail::whereHas('borrowing', function ($query) {
            $query->where('status', 'Dipinjam');
        })->whereNull('returned_at')->sum('quantity');

        // 3. Total Keseluruhan Aset (Tersedia + Dipinjam)
        $totalAssets = $availableStock + $totalItemsBorrowed;

        // 4. Produk dengan stok tipis (kurang dari 5) untuk notifikasi
        $lowStockProducts = Product::with('category')
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->get();

        // 5. Grafik Peminjaman per Bulan (6 bulan terakhir) - Kompatibel dengan MySQL & SQLite (untuk testing)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? 'CAST(strftime("%m", borrow_date) AS INTEGER)' : 'MONTH(borrow_date)';

        $monthlyData = DB::table('borrowings')
            ->select(DB::raw("$monthExpr as month"), DB::raw('COUNT(*) as count'))
            ->where('borrow_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy(DB::raw($monthExpr))
            ->orderBy(DB::raw($monthExpr))
            ->get();

        // Format nama bulan untuk Label Grafik (Indonesia)
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $chartLabels = [];
        $chartValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $targetMonth = $date->month;
            $chartLabels[] = $monthNames[$targetMonth] . ' ' . $date->year;
            
            $found = $monthlyData->firstWhere('month', $targetMonth);
            $chartValues[] = $found ? $found->count : 0;
        }

        // Peminjaman terbaru untuk tabel ringkasan dashboard
        $recentBorrowings = Borrowing::with(['processedBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalAssets',
            'totalItemsBorrowed',
            'availableStock',
            'lowStockProducts',
            'chartLabels',
            'chartValues',
            'recentBorrowings'
        ));
    }
}
