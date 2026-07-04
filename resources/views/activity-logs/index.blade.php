<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                    Log Aktivitas & Audit Trail
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lacak semua perubahan data, aktivitas user, dan transaksi secara real-time demi keamanan aset.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            <!-- Filter Pencarian -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm">
                <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kata Kunci -->
                    <div class="space-y-1">
                        <label for="search" class="text-xs font-bold text-gray-500 dark:text-gray-400">Cari Aktivitas / User</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama user atau detail log..." class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500/20 text-xs py-2.5 pl-8">
                            <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Filter Jenis Aksi -->
                    <div class="space-y-1">
                        <label for="action" class="text-xs font-bold text-gray-500 dark:text-gray-400">Filter Tindakan</label>
                        <select name="action" id="action" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500/20 text-xs py-2.5">
                            <option value="">Semua Tindakan</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                            Cari Log
                        </button>
                        <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Audit Logs -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="clean-table w-full">
                        <thead>
                            <tr>
                                <th style="width:180px">Waktu</th>
                                <th style="width:180px">Pelaku (User)</th>
                                <th style="width:160px">Tindakan</th>
                                <th>Deskripsi Aktivitas</th>
                                <th style="width:120px">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                    <!-- Tanggal & Waktu -->
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 font-medium">
                                        {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                    </td>
                                    
                                    <!-- Nama User + Role Badge -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($log->user)
                                            <div class="font-extrabold text-gray-900 dark:text-white">{{ $log->user->name }}</div>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider mt-0.5
                                                @if($log->user->role->slug === 'admin')
                                                    bg-rose-50 text-rose-700 border border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30
                                                @elseif($log->user->role->slug === 'manager')
                                                    bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30
                                                @else
                                                    bg-indigo-50 text-indigo-700 border border-indigo-100 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900/30
                                                @endif
                                            ">
                                                {{ $log->user->role->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic font-medium">Sistem Otomatis</span>
                                        @endif
                                    </td>

                                    <!-- Tindakan -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                            @if(str_contains($log->action, 'Tambah') || str_contains($log->action, 'Buat'))
                                                bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400
                                            @elseif(str_contains($log->action, 'Hapus') || str_contains($log->action, 'Permanent'))
                                                bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400
                                            @elseif(str_contains($log->action, 'Ubah') || str_contains($log->action, 'Edit') || str_contains($log->action, 'Kembalian'))
                                                bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400
                                            @else
                                                bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-350
                                            @endif
                                        ">
                                            {{ $log->action }}
                                        </span>
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $log->description }}
                                    </td>

                                    <!-- IP Address -->
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-500 dark:text-gray-400">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 dark:text-gray-500">
                                        Tidak ada catatan aktivitas yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-gray-150 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
