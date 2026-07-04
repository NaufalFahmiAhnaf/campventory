<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('borrowings.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                        Detail Peminjaman #{{ $borrowing->id }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lihat detail nota penyerahan dan informasi barang dipinjam.</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                @if((Auth::user()->isAdmin() || Auth::user()->isStaff()) && $borrowing->status !== 'Dikembalikan')
                    <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menandai seluruh barang ini telah dikembalikan ke gudang?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150">
                            Proses Pengembalian Barang
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full space-y-6">
            
            <!-- Ringkasan Informasi Peminjaman (Desain Nota) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <!-- Header Nota -->
                <div class="p-6 bg-gray-50 dark:bg-slate-850/30 border-b border-gray-100 dark:border-slate-850 flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">KOP NOTA DINAS</span>
                        <h3 class="text-lg font-black text-slate-850 dark:text-white tracking-tight">PT TELKOMSEL INLIFE INVENTARIS</h3>
                    </div>
                    <div>
                        @if($borrowing->display_status === 'Terlambat')
                            <span class="px-3 py-1 text-xs font-black bg-rose-50 text-rose-600 rounded-full border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">TERLAMBAT</span>
                        @elseif($borrowing->display_status === 'Dipinjam')
                            <span class="px-3 py-1 text-xs font-black bg-amber-50 text-amber-600 rounded-full border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">SEDANG DIPINJAM</span>
                        @else
                            <span class="px-3 py-1 text-xs font-black bg-emerald-55 text-emerald-600 rounded-full border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">TELAH DIKEMBALIKAN</span>
                        @endif
                    </div>
                </div>

                <!-- Info Fields -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Nama Peminjam</span>
                        <p class="mt-1 font-extrabold text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Diproses Oleh (Staff)</span>
                        <p class="mt-1 font-bold text-gray-800 dark:text-gray-250">{{ $borrowing->processedBy->name }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Tanggal Pinjam</span>
                        <p class="mt-1 text-gray-850 dark:text-gray-250">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->translatedFormat('d F Y') }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">Batas Pengembalian</span>
                        <p class="mt-1 text-gray-850 dark:text-gray-250">{{ \Carbon\Carbon::parse($borrowing->expected_return_date)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Barang yang Dipinjam -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4.5 border-b border-gray-100 dark:border-slate-850">
                    <h3 class="font-extrabold text-base text-gray-800 dark:text-white">Rincian Barang Dipinjam</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="clean-table">
                        <thead class="bg-gray-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-24">Gambar</th>
                                <th class="px-6 py-3 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-36">Kode Barang</th>
                                <th class="px-6 py-3 text-left text-xxs font-black text-slate-400 uppercase tracking-widest">Nama Barang Gunung</th>
                                <th class="px-6 py-3 text-left text-xxs font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-3 text-center text-xxs font-black text-slate-400 uppercase tracking-widest w-32">Kuantitas</th>
                                <th class="px-6 py-3 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-48">Tanggal Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-850">
                            @foreach($borrowing->details as $index => $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                    <!-- Gambar Thumbnail -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 overflow-hidden flex items-center justify-center">
                                            @if($detail->product->image_path)
                                                <img src="{{ asset('storage/' . $detail->product->image_path) }}" alt="{{ $detail->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Kode Barang -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $detail->product->code }}
                                    </td>

                                    <!-- Nama Barang -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-extrabold text-gray-800 dark:text-slate-200">
                                        {{ $detail->product->name }}
                                    </td>

                                    <!-- Kategori -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->product->category->name }}
                                    </td>

                                    <!-- Jumlah Dipinjam -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-center font-black text-gray-800 dark:text-white">
                                        {{ $detail->quantity }} unit
                                    </td>

                                    <!-- Tanggal Kembali -->
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-semibold">
                                        {{ $detail->returned_at ? \Carbon\Carbon::parse($detail->returned_at)->translatedFormat('d M Y H:i') : 'Belum Dikembalikan' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
