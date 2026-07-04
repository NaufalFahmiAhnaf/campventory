<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Aset - CampVentory</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }

        .header { text-align: center; padding: 20px 0 10px; border-bottom: 3px solid #e11d48; margin-bottom: 15px; }
        .header h1 { font-size: 16px; font-weight: 800; color: #e11d48; letter-spacing: 1px; }
        .header p { font-size: 10px; color: #64748b; margin-top: 4px; }
        .header .date { font-size: 9px; color: #94a3b8; margin-top: 8px; }

        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 15px; margin-bottom: 15px; }
        .summary-box span { font-size: 10px; color: #64748b; margin-right: 20px; }
        .summary-box strong { color: #1e293b; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #1e293b; color: white; text-align: left; padding: 7px 8px; font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9px; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .status-dipinjam { color: #f59e0b; font-weight: 700; }
        .status-dikembalikan { color: #16a34a; font-weight: 700; }
        .status-terlambat { color: #dc2626; font-weight: 700; }

        .items-list { margin: 0; padding-left: 12px; }
        .items-list li { margin-bottom: 2px; }

        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT TELKOMSEL INLIFE — CAMPVENTORY</h1>
        <p>Laporan Riwayat Transaksi Peminjaman Aset Perlengkapan Gunung</p>
        <div class="date">Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
    </div>

    <div class="summary-box">
        <span>Total Transaksi: <strong>{{ $borrowings->count() }}</strong></span>
        <span>Masih Dipinjam: <strong>{{ $borrowings->where('status', 'Dipinjam')->count() }}</strong></span>
        <span>Sudah Dikembalikan: <strong>{{ $borrowings->where('status', 'Dikembalikan')->count() }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px">No</th>
                <th style="width: 55px">ID</th>
                <th style="width: 120px">Peminjam</th>
                <th style="width: 70px">Tgl Pinjam</th>
                <th style="width: 70px">Batas Kembali</th>
                <th style="width: 65px">Status</th>
                <th style="width: 100px">Diproses Oleh</th>
                <th>Daftar Barang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $index => $borrowing)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: 600;">#{{ $borrowing->id }}</td>
                <td style="font-weight: 600;">{{ $borrowing->borrower_name }}</td>
                <td>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($borrowing->expected_return_date)->format('d/m/Y') }}</td>
                <td>
                    @if($borrowing->status === 'Dipinjam')
                        @if($borrowing->expected_return_date < now()->toDateString())
                            <span class="status-terlambat">Terlambat</span>
                        @else
                            <span class="status-dipinjam">Dipinjam</span>
                        @endif
                    @else
                        <span class="status-dikembalikan">Dikembalikan</span>
                    @endif
                </td>
                <td>{{ $borrowing->processedBy->name }}</td>
                <td>
                    <ul class="items-list">
                        @foreach($borrowing->details as $detail)
                            <li>{{ $detail->product->name }} ({{ $detail->quantity }} unit)</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8;">Tidak ada data peminjaman untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Inventaris CampVentory — PT Telkomsel InLife
    </div>
</body>
</html>
