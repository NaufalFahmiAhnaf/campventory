<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                    Inventaris Alat Gunung
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data master barang gunung, stok, lokasi penyimpanan, dan kondisinya.</p>
            </div>
            
            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-telkomsel-500 hover:bg-telkomsel-600 text-white text-sm font-bold rounded-xl shadow-md shadow-telkomsel-500/20 hover:shadow-lg transition duration-150">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Barang
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="space-y-6">
            


            <!-- Pencarian dan Filter -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm">
                <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Kata Kunci -->
                    <div class="space-y-1">
                        <label for="search" class="text-xs font-bold text-gray-500 dark:text-gray-400">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama, kode, lokasi..." class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2.5 pl-8">
                            <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1">
                        <label for="category_id" class="text-xs font-bold text-gray-500 dark:text-gray-400">Kategori</label>
                        <select name="category_id" id="category_id" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2.5">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kondisi -->
                    <div class="space-y-1">
                        <label for="condition" class="text-xs font-bold text-gray-500 dark:text-gray-400">Kondisi</label>
                        <select name="condition" id="condition" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2.5">
                            <option value="">Semua Kondisi</option>
                            <option value="Baru" {{ request('condition') === 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Baik" {{ request('condition') === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ request('condition') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ request('condition') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    <!-- Tombol Cari & Reset -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition duration-150">
                            Cari Barang
                        </button>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition duration-150" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
                
                <!-- Quick filter status stok menipis -->
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-slate-800 flex flex-wrap gap-2 items-center">
                    <span class="text-xxs font-extrabold uppercase text-gray-400 tracking-wider">Pintasan Filter:</span>
                    <a href="{{ request()->fullUrlWithQuery(['filter_stock' => 'low']) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ request('filter_stock') === 'low' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Stok Menipis (< 5)
                    </a>
                </div>
            </div>

            <!-- Tabel Data Master Barang -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th style="width:70px">Gambar</th>
                                <th style="width:130px">Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th style="width:100px">Stok</th>
                                <th style="width:130px">Lokasi</th>
                                <th style="width:110px">Kondisi</th>
                                <th style="width:200px; text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $prod)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                    <!-- Gambar Thumbnail -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 overflow-hidden flex items-center justify-center">
                                            @if($prod->image_path)
                                                <img src="{{ asset('storage/' . $prod->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                            @else
                                                <!-- Camping Gear Icon Placeholder -->
                                                <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Kode Barang -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-gray-800 dark:text-slate-200">
                                        {{ $prod->code }}
                                    </td>

                                    <!-- Nama Barang -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $prod->name }}</div>
                                        @if($prod->description)
                                            <div class="text-xs text-gray-400 max-w-xs truncate">{{ $prod->description }}</div>
                                        @endif
                                    </td>

                                    <!-- Kategori -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prod->category->name }}
                                    </td>

                                    <!-- Stok -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        @if($prod->stock < 5)
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400 px-2 py-0.5 rounded-lg border border-rose-150 dark:border-rose-900/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                                {{ $prod->stock }} unit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400 px-2 py-0.5 rounded-lg border border-emerald-150 dark:border-emerald-900/50">
                                                {{ $prod->stock }} unit
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Lokasi Penyimpanan -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $prod->storage_location }}
                                    </td>

                                    <!-- Kondisi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($prod->condition === 'Baru')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">Baru</span>
                                        @elseif($prod->condition === 'Baik')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300">Baik</span>
                                        @elseif($prod->condition === 'Rusak Ringan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300">Rusak Ringan</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300">Rusak Berat</span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                                        <div class="table-action-group">
                                            <a href="{{ route('products.show', $prod->id) }}" class="table-action table-action-neutral" title="Detail Barang">
                                                Detail
                                            </a>
                                            
                                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                                <a href="{{ route('products.edit', $prod->id) }}" class="table-action table-action-primary" title="Edit Barang">
                                                    Edit
                                                </a>
                                                
                                                <form action="{{ route('products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini dari database?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="table-action table-action-danger" title="Hapus Barang">
                                                        Hapus
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
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            Tidak ada barang ditemukan untuk kriteria pencarian ini.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-900/50">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
