<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                    Transaksi Peminjaman Aset
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau pendistribusian barang, tanggal jatuh tempo pengembalian, dan riwayat peminjaman.</p>
            </div>
            
            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-4 py-2 bg-telkomsel-500 hover:bg-telkomsel-600 text-white text-sm font-bold rounded-xl shadow-md shadow-telkomsel-500/20 hover:shadow-lg transition duration-150">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Peminjaman Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            


            <!-- Filter Pencarian -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm">
                <form action="{{ route('borrowings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Nama Peminjam -->
                    <div class="space-y-1">
                        <label for="search" class="text-xs font-bold text-gray-500 dark:text-gray-400">Cari Peminjam</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan nama peminjam..." class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2.5 pl-8">
                            <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div class="space-y-1">
                        <label for="status" class="text-xs font-bold text-gray-500 dark:text-gray-400">Status Peminjaman</label>
                        <select name="status" id="status" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2.5">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                            Cari Transaksi
                        </button>
                        <a href="{{ route('borrowings.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Peminjaman -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Nama Peminjam</th>
                                <th>Barang Dipinjam</th>
                                <th style="width:130px">Tgl Pinjam</th>
                                <th style="width:130px">Batas Kembali</th>
                                <th style="width:150px">Diproses Oleh</th>
                                <th style="width:100px; text-align:center">Status</th>
                                <th style="width:180px; text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowings as $index => $borrowing)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                    <!-- Nomor -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-500 dark:text-slate-400">
                                        {{ $borrowings->firstItem() + $index }}
                                    </td>
                                    
                                    <!-- Nama Peminjam -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-gray-900 dark:text-white">
                                        {{ $borrowing->borrower_name }}
                                    </td>

                                    <!-- Barang yang Dipinjam (Ringkasan Detail) -->
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs">
                                        <div class="space-y-1">
                                            @foreach($borrowing->details->take(2) as $det)
                                                <div class="flex items-center gap-1 font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    <span class="truncate">{{ $det->product->name }}</span>
                                                    <span class="text-xs text-gray-400 font-extrabold">({{ $det->quantity }}x)</span>
                                                </div>
                                            @endforeach
                                            @if($borrowing->details->count() > 2)
                                                <div class="text-xxs text-slate-400 font-bold italic pl-2.5">
                                                    + {{ $borrowing->details->count() - 2 }} barang lainnya...
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Tanggal Pinjam -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($borrowing->borrow_date)->translatedFormat('d M Y') }}
                                    </td>

                                    <!-- Batas Pengembalian -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($borrowing->expected_return_date)->translatedFormat('d M Y') }}
                                    </td>

                                    <!-- Diproses Oleh -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-semibold">
                                        {{ $borrowing->processedBy->name }}
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($borrowing->display_status === 'Terlambat')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-300">Terlambat</span>
                                        @elseif($borrowing->display_status === 'Dipinjam')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300">Dipinjam</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">Kembali</span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                                        <div class="table-action-group">
                                            <a href="{{ route('borrowings.show', $borrowing->id) }}" class="table-action table-action-neutral" title="Lihat Detail Transaksi">
                                                Detail
                                            </a>
                                            
                                            @if((Auth::user()->isAdmin() || Auth::user()->isStaff()) && $borrowing->status !== 'Dikembalikan')
                                                <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menandai seluruh barang dalam peminjaman ini telah dikembalikan?');">
                                                    @csrf
                                                    <button type="submit" class="table-action table-action-success">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400 font-medium">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            Tidak ada transaksi peminjaman terdaftar.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($borrowings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-900/50">
                        {{ $borrowings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
