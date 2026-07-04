<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                Catat Peminjaman Barang
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat formulir pencatatan serah terima peminjaman barang gunung ke staff/peminjam.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            


            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                <form action="{{ route('borrowings.store') }}" method="POST" id="borrow-form" class="p-6 space-y-6">
                    @csrf

                    <!-- BAGIAN ATAS: INFORMASI UTAMA PEMINJAM -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Nama Peminjam -->
                        <div class="space-y-1.5">
                            <label for="borrower_name" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                Nama Peminjam <span class="text-telkomsel-500">*</span>
                            </label>
                            <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                            @error('borrower_name')
                                <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Pinjam -->
                        <div class="space-y-1.5">
                            <label for="borrow_date" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                Tanggal Peminjaman <span class="text-telkomsel-500">*</span>
                            </label>
                            <input type="date" name="borrow_date" id="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                            @error('borrow_date')
                                <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Batas Pengembalian -->
                        <div class="space-y-1.5">
                            <label for="expected_return_date" class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                Batas Pengembalian <span class="text-telkomsel-500">*</span>
                            </label>
                            <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date') }}" class="w-full rounded-xl border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-sm py-2.5" required>
                            @error('expected_return_date')
                                <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- BAGIAN BAWAH: DAFTAR BARANG YANG DIPINJAM (TABEL DINAMIS) -->
                    <div class="border-t border-gray-100 dark:border-slate-850 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-extrabold text-base text-gray-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-telkomsel-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Detail Item Peminjaman
                            </h3>
                            <button type="button" id="add-row-btn" class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Item Barang
                            </button>
                        </div>

                        <div class="border border-gray-150 dark:border-slate-800 rounded-xl overflow-hidden shadow-xs">
                            <table class="clean-table" id="items-table">
                                <thead>
                                    <tr>
                                        <th>Nama Barang Gunung</th>
                                        <th style="width: 160px">Stok Tersedia</th>
                                        <th style="width: 144px">Jumlah Pinjam</th>
                                        <th style="width: 80px; text-align: center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-tbody">
                                    <!-- Baris Pertama Form -->
                                    <tr class="item-row">
                                        <td class="p-3">
                                            <select name="items[0][product_id]" class="product-select w-full rounded-xl border-gray-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2" required onchange="handleProductChange(this)">
                                                <option value="" disabled selected>Pilih Barang</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->code }} - {{ $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-3">
                                            <span class="stock-display text-sm font-bold text-slate-500 dark:text-slate-400">-</span>
                                        </td>
                                        <td class="p-3">
                                            <input type="number" name="items[0][quantity]" class="qty-input w-full rounded-xl border-gray-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2" value="1" min="1" required>
                                        </td>
                                        <td class="p-3 text-center">
                                            <button type="button" class="remove-row-btn text-rose-500 hover:text-rose-700 transition" onclick="removeRow(this)">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-850">
                        <a href="{{ route('borrowings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-250 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-telkomsel-500 hover:bg-telkomsel-600 text-white text-sm font-bold rounded-xl shadow-md shadow-telkomsel-500/20 hover:shadow-lg transition">
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Dinamis Kelola Baris Tabel -->
    <script>
        let rowCount = 1;

        // Handler saat produk terpilih berubah stoknya
        function handleProductChange(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const row = selectElement.closest('.item-row');
            
            const stockDisplay = row.querySelector('.stock-display');
            const qtyInput = row.querySelector('.qty-input');

            if (stock !== null) {
                stockDisplay.textContent = stock + ' unit';
                qtyInput.max = stock; // Batasi kuantitas input sesuai stok yang tersedia
                qtyInput.placeholder = "Max " + stock;
            } else {
                stockDisplay.textContent = '-';
                qtyInput.removeAttribute('max');
                qtyInput.placeholder = '';
            }
        }

        // Hapus baris peminjaman barang
        function removeRow(buttonElement) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                buttonElement.closest('.item-row').remove();
            } else {
                alert('Anda harus meminjam minimal 1 jenis barang!');
            }
        }

        document.getElementById('add-row-btn').addEventListener('click', function() {
            const tbody = document.getElementById('items-tbody');
            
            // Definisikan HTML untuk baris baru
            const newRowHtml = `
                <tr class="item-row">
                    <td class="p-3">
                        <select name="items[${rowCount}][product_id]" class="product-select w-full rounded-xl border-gray-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2" required onchange="handleProductChange(this)">
                            <option value="" disabled selected>Pilih Barang</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->code }} - {{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-3">
                        <span class="stock-display text-sm font-bold text-slate-500 dark:text-slate-400">-</span>
                    </td>
                    <td class="p-3">
                        <input type="number" name="items[${rowCount}][quantity]" class="qty-input w-full rounded-xl border-gray-250 dark:border-slate-800 bg-white dark:bg-slate-950 text-gray-900 dark:text-white focus:border-telkomsel-500 focus:ring-telkomsel-500/20 text-xs py-2" value="1" min="1" required>
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" class="remove-row-btn text-rose-500 hover:text-rose-700 transition" onclick="removeRow(this)">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', newRowHtml);
            rowCount++;
        });

        // Validasi form sebelum disubmit agar tidak ada produk duplikat yang diinput secara manual
        document.getElementById('borrow-form').addEventListener('submit', function(e) {
            const selects = document.querySelectorAll('.product-select');
            const selectedIds = [];
            let duplicates = false;

            selects.forEach(select => {
                const val = select.value;
                if (val) {
                    if (selectedIds.includes(val)) {
                        duplicates = true;
                    }
                    selectedIds.push(val);
                }
            });

            if (duplicates) {
                e.preventDefault();
                alert('Terdapat duplikasi barang dalam daftar peminjaman Anda. Silakan satukan jumlah peminjaman barang yang sejenis dalam satu baris.');
            }
        });
    </script>
</x-app-layout>
