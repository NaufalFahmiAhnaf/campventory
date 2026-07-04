<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman menu laporan utama.
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('reports.index', compact('categories'));
    }

    /**
     * Ekspor daftar barang gunung ke format PDF.
     */
    public function exportProductsPdf(Request $request)
    {
        $query = Product::with('category');
        $hasDateRange = $request->filled('start_date') || $request->filled('end_date');

        if ($hasDateRange) {
            $query->withSum(['borrowingDetails as borrowed_period_quantity' => function ($detailQuery) use ($request) {
                $detailQuery->whereHas('borrowing', function ($borrowingQuery) use ($request) {
                    if ($request->filled('start_date')) {
                        $borrowingQuery->whereDate('borrow_date', '>=', $request->start_date);
                    }

                    if ($request->filled('end_date')) {
                        $borrowingQuery->whereDate('borrow_date', '<=', $request->end_date);
                    }
                });
            }], 'quantity');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('code', 'asc')->get();
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $categoryName = $request->filled('category_id') ? Category::find($request->category_id)?->name : null;
        
        // Pemuatan view PDF laporan dengan konfigurasi portrait A4
        $pdf = Pdf::loadView('reports.pdf_products', compact('products', 'startDate', 'endDate', 'categoryName', 'hasDateRange'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Stok_Barang_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Ekspor daftar barang gunung ke format Excel (CSV).
     */
    public function exportProductsExcel(Request $request)
    {
        $query = Product::with('category');
        $hasDateRange = $request->filled('start_date') || $request->filled('end_date');

        if ($hasDateRange) {
            $query->withSum(['borrowingDetails as borrowed_period_quantity' => function ($detailQuery) use ($request) {
                $detailQuery->whereHas('borrowing', function ($borrowingQuery) use ($request) {
                    if ($request->filled('start_date')) {
                        $borrowingQuery->whereDate('borrow_date', '>=', $request->start_date);
                    }

                    if ($request->filled('end_date')) {
                        $borrowingQuery->whereDate('borrow_date', '<=', $request->end_date);
                    }
                });
            }], 'quantity');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('code', 'asc')->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Laporan_Stok_Barang_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($products, $hasDateRange) {
            $file = fopen('php://output', 'w');
            // Tulis Byte Order Mark (BOM) UTF-8 agar Excel mendeteksi encoding dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header kolom
            $headers = ['Kode Barang', 'Nama Barang', 'Kategori', 'Stok Tersedia'];
            if ($hasDateRange) {
                $headers[] = 'Dipinjam Dalam Periode';
            }
            $headers = array_merge($headers, ['Lokasi Penyimpanan', 'Kondisi', 'Deskripsi']);

            fputcsv($file, $headers);
            
            foreach ($products as $prod) {
                $row = [
                    $prod->code,
                    $prod->name,
                    $prod->category->name,
                    $prod->stock,
                ];

                if ($hasDateRange) {
                    $row[] = (int) ($prod->borrowed_period_quantity ?? 0);
                }

                $row = array_merge($row, [
                    $prod->storage_location,
                    $prod->condition,
                    $prod->description ?? '-'
                ]);

                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor daftar peminjaman ke format PDF dengan filter tanggal/status.
     */
    public function exportBorrowingsPdf(Request $request)
    {
        $query = Borrowing::with(['processedBy', 'details.product.category']);

        // Terapkan filter tanggal jika diisi
        if ($request->filled('start_date')) {
            $query->whereDate('borrow_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('borrow_date', '<=', $request->end_date);
        }

        $query->filterByDisplayStatus($request->input('status'));

        $borrowings = $query->orderBy('borrow_date', 'asc')->get();

        // Pemuatan view PDF laporan peminjaman dengan landscape A4 karena kolomnya lebar
        $pdf = Pdf::loadView('reports.pdf_borrowings', compact('borrowings'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Peminjaman_Aset_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Ekspor daftar peminjaman ke format Excel (CSV) dengan filter tanggal/status.
     */
    public function exportBorrowingsExcel(Request $request)
    {
        $query = Borrowing::with(['processedBy', 'details.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('borrow_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('borrow_date', '<=', $request->end_date);
        }
        $query->filterByDisplayStatus($request->input('status'));

        $borrowings = $query->orderBy('borrow_date', 'asc')->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Laporan_Peminjaman_Aset_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($borrowings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header kolom
            fputcsv($file, ['ID Peminjaman', 'Nama Peminjam', 'Tanggal Pinjam', 'Batas Kembali', 'Status', 'Diproses Oleh Staf', 'Daftar Barang & Jumlah']);
            
            foreach ($borrowings as $b) {
                $detailsArray = [];
                foreach ($b->details as $det) {
                    $detailsArray[] = $det->product->name . ' (' . $det->quantity . ' unit)';
                }
                $detailsString = implode(', ', $detailsArray);

                fputcsv($file, [
                    '#PINJAM-' . $b->id,
                    $b->borrower_name,
                    $b->borrow_date->format('Y-m-d'),
                    $b->expected_return_date->format('Y-m-d'),
                    $b->display_status,
                    $b->processedBy->name,
                    $detailsString
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
