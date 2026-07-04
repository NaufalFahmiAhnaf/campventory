<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang - CampVentory</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }

        .header { text-align: center; padding: 20px 0 10px; border-bottom: 3px solid #e11d48; margin-bottom: 15px; }
        .header h1 { font-size: 18px; font-weight: 800; color: #e11d48; letter-spacing: 1px; }
        .header p { font-size: 10px; color: #64748b; margin-top: 4px; }
        .header .date { font-size: 9px; color: #94a3b8; margin-top: 8px; }

        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 15px; margin-bottom: 15px; display: flex; }
        .summary-box span { font-size: 10px; color: #64748b; margin-right: 20px; }
        .summary-box strong { color: #1e293b; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #1e293b; color: white; text-align: left; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .stock-low { color: #dc2626; font-weight: 700; }
        .stock-ok { color: #16a34a; font-weight: 700; }

        .condition-baru,
        .condition-baik { color: #16a34a; font-weight: 700; }
        .condition-rusak-ringan { color: #f59e0b; font-weight: 700; }
        .condition-rusak-berat { color: #dc2626; font-weight: 700; }

        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT TELKOMSEL INLIFE — CAMPVENTORY</h1>
        <p>Laporan Data Stok Barang Inventaris Perlengkapan Gunung</p>
        <div class="date">Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
    </div>

    <div class="summary-box">
        <span>Total Jenis Barang: <strong>{{ $products->count() }}</strong></span>
        <span>Total Keseluruhan Stok: <strong>{{ $products->sum('stock') }} unit</strong></span>
        @if($hasDateRange)
            <span>Dipinjam Dalam Periode: <strong>{{ $products->sum('borrowed_period_quantity') }} unit</strong></span>
        @endif
        @if(isset($startDate) && $startDate)
            <span>Mulai: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong></span>
        @endif
        @if(isset($endDate) && $endDate)
            <span>Akhir: <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong></span>
        @endif
        @if(isset($categoryName) && $categoryName)
            <span>Kategori: <strong>{{ $categoryName }}</strong></span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th style="width: 80px">Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th style="width: 55px; text-align: center;">Stok</th>
                @if($hasDateRange)
                    <th style="width: 70px; text-align: center;">Dipinjam</th>
                @endif
                <th>Lokasi</th>
                <th style="width: 75px">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: 600;">{{ $product->code }}</td>
                <td style="font-weight: 600;">{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td style="text-align: center;" class="{{ $product->stock < 5 ? 'stock-low' : 'stock-ok' }}">
                    {{ $product->stock }}
                </td>
                @if($hasDateRange)
                    <td style="text-align: center; font-weight: 700;">
                        {{ (int) ($product->borrowed_period_quantity ?? 0) }}
                    </td>
                @endif
                <td>{{ $product->storage_location }}</td>
                <td class="{{ match($product->condition) { 'Baru' => 'condition-baru', 'Baik' => 'condition-baik', 'Rusak Ringan' => 'condition-rusak-ringan', default => 'condition-rusak-berat' } }}">
                    {{ $product->condition }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $hasDateRange ? 8 : 7 }}" style="text-align: center; padding: 20px; color: #94a3b8;">Tidak ada data stok untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Inventaris CampVentory — PT Telkomsel InLife
    </div>
</body>
</html>
