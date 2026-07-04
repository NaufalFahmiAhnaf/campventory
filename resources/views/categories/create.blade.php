<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                Tambah Kategori Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat kategori baru untuk memisahkan jenis barang gunung di inventaris.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Nama Kategori -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                            Nama Kategori <span class="text-telkomsel-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama kategori (contoh: Peralatan Tidur)" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                        @error('name')
                            <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                            Deskripsi Kategori
                        </label>
                        <textarea name="description" id="description" rows="4" placeholder="Tulis penjelasan singkat mengenai kategori barang ini..." class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-250 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-xl transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-telkomsel-500 hover:bg-telkomsel-600 text-white text-sm font-bold rounded-xl shadow-md shadow-telkomsel-500/20 hover:shadow-lg transition duration-150">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
