<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                        Detail Barang: {{ $product->name }}
                    </h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lihat detail spesifikasi barang gunung dan riwayat peminjamannya.</p>
            </div>
            
            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                <div class="flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition shadow-sm">
                        Edit Barang
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full space-y-6">
            
            <!-- Detail Informasi Utama (Dua Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Foto & Status Singkat (Lebar 1/3) -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm flex flex-col items-center">
                    <div class="w-full aspect-square rounded-2xl bg-gray-50 dark:bg-slate-950 border border-gray-150 dark:border-slate-800 overflow-hidden flex items-center justify-center mb-6">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-24 h-24 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        @endif
                    </div>
                    
                    <!-- Badge Ketersediaan -->
                    <div class="w-full space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-850">
                            <span class="text-xs font-bold text-gray-400 uppercase">Status Stok</span>
                            @if($product->stock < 5)
                                <span class="px-3 py-1 text-xs font-bold bg-rose-50 text-rose-600 rounded-full border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">Stok Menipis</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Tersedia</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-850">
                            <span class="text-xs font-bold text-gray-400 uppercase">Kondisi Alat</span>
                            @if($product->condition === 'Baru')
                                <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-full dark:bg-emerald-950/40 dark:text-emerald-350">Baru</span>
                            @elseif($product->condition === 'Baik')
                                <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full dark:bg-blue-950/40 dark:text-blue-350">Baik</span>
                            @elseif($product->condition === 'Rusak Ringan')
                                <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full dark:bg-amber-950/40 dark:text-amber-355">Rusak Ringan</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold bg-rose-100 text-rose-800 rounded-full dark:bg-rose-950/40 dark:text-rose-350">Rusak Berat</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold text-gray-400 uppercase">Stok Gudang</span>
                            <span class="text-lg font-black text-gray-800 dark:text-white">{{ $product->stock }} unit</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Informasi Lengkap (Lebar 2/3) -->
                <div class="md:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-lg text-gray-800 dark:text-white mb-6 border-b border-gray-100 dark:border-slate-850 pb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-telkomsel-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Spesifikasi Barang
                        </h3>

                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase">Kode Barang</dt>
                                <dd class="mt-1 text-sm font-black text-gray-800 dark:text-white tracking-wider">{{ $product->code }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase">Nama Barang</dt>
                                <dd class="mt-1 text-sm font-bold text-gray-800 dark:text-white">{{ $product->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-800 dark:text-gray-250">{{ $product->category->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs font-bold text-gray-400 uppercase">Lokasi Penyimpanan</dt>
                                <dd class="mt-1 text-sm text-gray-800 dark:text-gray-250 font-semibold">{{ $product->storage_location }}</dd>
                            </div>

                            <div class="sm:col-span-2 border-t border-gray-100 dark:border-slate-850 pt-4">
                                <dt class="text-xs font-bold text-gray-400 uppercase">Deskripsi / Keterangan Tambahan</dt>
                                <dd class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">{{ $product->description ?? 'Tidak ada catatan tambahan.' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-t border-gray-100 dark:border-slate-850 pt-6 mt-6 flex justify-between items-center text-xs text-gray-400">
                        <span>Ditambahkan pada: {{ $product->created_at->translatedFormat('d F Y H:i') }}</span>
                        <span>Terakhir diperbarui: {{ $product->updated_at->translatedFormat('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Peminjaman Barang -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-850">
                    <h3 class="font-extrabold text-lg text-gray-800 dark:text-white">Riwayat Peminjaman Barang Ini</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Daftar transaksi peminjaman alat gunung ini oleh seluruh staff/karyawan</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="clean-table">
                        <thead class="bg-gray-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                                <th class="px-6 py-3.5 text-left text-xxs font-black text-slate-400 uppercase tracking-widest">Nama Peminjam</th>
                                <th class="px-6 py-3.5 text-center text-xxs font-black text-slate-400 uppercase tracking-widest w-28">Jumlah Pinjam</th>
                                <th class="px-6 py-3.5 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-40">Tanggal Pinjam</th>
                                <th class="px-6 py-3.5 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-40">Batas Kembali</th>
                                <th class="px-6 py-3.5 text-left text-xxs font-black text-slate-400 uppercase tracking-widest w-40">Tanggal Dikembalikan</th>
                                <th class="px-6 py-3.5 text-center text-xxs font-black text-slate-400 uppercase tracking-widest w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-850">
                            @forelse($product->borrowingDetails as $index => $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-500 dark:text-slate-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-gray-900 dark:text-white">
                                        {{ $detail->borrowing->borrower_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-800 dark:text-slate-200">
                                        {{ $detail->quantity }} unit
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($detail->borrowing->borrow_date)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($detail->borrowing->expected_return_date)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detail->returned_at ? \Carbon\Carbon::parse($detail->returned_at)->translatedFormat('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($detail->borrowing->display_status === 'Dikembalikan')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-emerald-55 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400">
                                                Dikembalikan
                                            </span>
                                        @elseif($detail->borrowing->display_status === 'Terlambat')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-rose-50 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-amber-50 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400">
                                                Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 font-medium">
                                        Belum ada riwayat peminjaman untuk barang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
