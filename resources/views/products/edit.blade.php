<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                Edit Barang: {{ $product->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui data master, kapasitas stok, lokasi penyimpanan, maupun kondisi fisik barang.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SISI KIRI: DATA UTAMA -->
                        <div class="space-y-5">
                            <!-- Kode Barang (Wajib diisi pada edit) -->
                            <div class="space-y-1.5">
                                <label for="code" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                    Kode Barang <span class="text-telkomsel-500">*</span>
                                </label>
                                <input type="text" name="code" id="code" value="{{ old('code', $product->code) }}" placeholder="Contoh: TSEL-TND-001" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                @error('code')
                                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Barang -->
                            <div class="space-y-1.5">
                                <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                    Nama Barang <span class="text-telkomsel-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama alat gunung" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                @error('name')
                                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="space-y-1.5">
                                <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                    Kategori Barang <span class="text-telkomsel-500">*</span>
                                </label>
                                <select name="category_id" id="category_id" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stok & Lokasi Penyimpanan -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="stock" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                        Stok <span class="text-telkomsel-500">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                    @error('stock')
                                        <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label for="storage_location" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                        Lokasi Rak <span class="text-telkomsel-500">*</span>
                                    </label>
                                    <input type="text" name="storage_location" id="storage_location" value="{{ old('storage_location', $product->storage_location) }}" placeholder="Contoh: Rak A-1" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                    @error('storage_location')
                                        <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SISI KANAN: KONDISI & GAMBAR -->
                        <div class="space-y-5">
                            <!-- Kondisi Barang -->
                            <div class="space-y-1.5">
                                <label for="condition" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                    Kondisi Barang <span class="text-telkomsel-500">*</span>
                                </label>
                                <select name="condition" id="condition" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                                    <option value="Baru" {{ old('condition', $product->condition) === 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('condition', $product->condition) === 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('condition', $product->condition) === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ old('condition', $product->condition) === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('condition')
                                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Gambar + Preview -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 font-semibold">Gambar Barang</label>
                                <div class="flex flex-col items-center p-4 border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl bg-gray-50 dark:bg-slate-950 text-center">
                                    
                                    <!-- Image Preview Container -->
                                    <div id="preview-container" class="w-32 h-32 mb-3 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                                        @if($product->image_path)
                                            <img id="image-preview" src="{{ asset('storage/' . $product->image_path) }}" alt="Pratinjau Gambar" class="w-full h-full object-cover">
                                        @else
                                            <img id="image-preview" src="#" alt="Pratinjau Gambar" class="w-full h-full object-cover hidden">
                                            <svg id="upload-icon" class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="flex text-xs text-gray-600 dark:text-gray-400">
                                        <label for="image" class="relative cursor-pointer bg-white dark:bg-slate-900 rounded-md font-bold text-telkomsel-500 hover:text-telkomsel-600 focus-within:outline-none">
                                            <span>Ubah gambar baru</span>
                                            <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this);">
                                        </label>
                                        <p class="pl-1">atau seret file ke sini</p>
                                    </div>
                                    <p class="text-xxs text-gray-400 mt-1 font-semibold">PNG, JPG, JPEG, WEBP hingga 2MB</p>
                                </div>
                                @error('image')
                                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan Deskripsi (Lebar Penuh) -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-200">Deskripsi / Keterangan Barang</label>
                        <textarea name="description" id="description" rows="3" placeholder="Tuliskan catatan tambahan mengenai barang gunung ini..." class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-250 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-telkomsel-500 hover:bg-telkomsel-600 text-white text-sm font-bold rounded-xl shadow-md shadow-telkomsel-500/20 hover:shadow-lg transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Image Preview -->
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const icon = document.getElementById('upload-icon');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(icon) icon.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
