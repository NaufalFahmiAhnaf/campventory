<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
                Dashboard Inventaris
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selamat datang kembali, {{ Auth::user()->name }}. Kelola aset outdoor dengan efisien.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full space-y-6">
            
            <!-- Notifikasi Stok Menipis -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Warning Icon -->
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <div>
                                <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Pemberitahuan Stok Menipis!</p>
                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-400">Ada {{ $lowStockProducts->count() }} barang gunung dengan jumlah stok kurang dari 5 unit. Segera lakukan pengisian ulang barang agar operasional rental/peminjaman tidak terganggu.</p>
                            </div>
                            <p class="mt-3 md:mt-0 text-xs md:ml-6 flex-shrink-0">
                                <a href="{{ route('products.index', ['filter_stock' => 'low']) }}" class="whitespace-nowrap font-semibold text-amber-800 dark:text-amber-300 hover:text-amber-900 underline transition">
                                    Lihat Semua Barang &rarr;
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kartu Metrik Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Barang -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-200 opacity-0 animate-slide-up stagger-1">
                    <div class="absolute -right-4 -bottom-4 text-gray-100 dark:text-slate-850 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1V7zm10 8a1 1 0 11-2 0 1 1 0 012 0zm-4-1a1 1 0 100 2h3a1 1 0 100-2h-3z"></path></svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Total Aset Fisik</span>
                        <span class="p-2 rounded-xl bg-telkomsel-50 dark:bg-telkomsel-950/30 text-telkomsel-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalAssets }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Seluruh unit alat gunung yang terdaftar</p>
                    </div>
                </div>

                <!-- Barang Dipinjam -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-200 opacity-0 animate-slide-up stagger-2">
                    <div class="absolute -right-4 -bottom-4 text-gray-100 dark:text-slate-850 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Sedang Dipinjam</span>
                        <span class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalItemsBorrowed }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Unit alat yang berada di luar gudang</p>
                    </div>
                </div>

                <!-- Barang Tersedia -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-200 opacity-0 animate-slide-up stagger-3">
                    <div class="absolute -right-4 -bottom-4 text-gray-100 dark:text-slate-850 group-hover:scale-110 transition-transform duration-300 pointer-events-none">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Stok Tersedia</span>
                        <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-3xl font-black text-gray-800 dark:text-white">{{ $availableStock }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Unit alat siap dipinjam di dalam gudang</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Dua Kolom (Grafik & Aktivitas) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Grafik Peminjaman (Lebar 2/3) -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-extrabold text-lg text-gray-800 dark:text-white">Grafik Transaksi Peminjaman</h3>
                            <span class="text-xs bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 px-3 py-1.5 rounded-full font-semibold">6 Bulan Terakhir</span>
                        </div>
                        <div class="relative h-[300px]">
                            <canvas id="borrowingsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Peminjaman Terbaru (Lebar 1/3) -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm flex flex-col">
                    <h3 class="font-extrabold text-lg text-gray-800 dark:text-white mb-4">Aktivitas Peminjaman Baru</h3>
                    
                    <div class="flow-root flex-1 animate-fade-in">
                        @if($recentBorrowings->count() > 0)
                            <ul role="list" class="-my-4 divide-y divide-gray-100 dark:divide-slate-800">
                                @foreach($recentBorrowings as $borrowing)
                                    <li class="py-3.5">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($borrowing->status === 'Dipinjam')
                                                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </span>
                                                @elseif($borrowing->status === 'Dikembalikan')
                                                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-rose-50 dark:bg-rose-950/30 text-rose-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate">
                                                    {{ $borrowing->borrower_name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    Tanggal: {{ \Carbon\Carbon::parse($borrowing->borrow_date)->translatedFormat('d M Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                @if($borrowing->status === 'Dipinjam')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-amber-50 dark:bg-amber-950/30 text-amber-600 border border-amber-100 dark:border-amber-900/50">
                                                        Pinjam
                                                    </span>
                                                @elseif($borrowing->status === 'Dikembalikan')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 border border-emerald-100 dark:border-emerald-900/50">
                                                        Kembali
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-extrabold bg-rose-50 dark:bg-rose-950/30 text-rose-600 border border-rose-100 dark:border-rose-900/50">
                                                        Telat
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-full flex flex-col justify-center items-center py-10 text-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Belum ada transaksi peminjaman.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-800">
                        <a href="{{ route('borrowings.index') }}" class="w-full text-center block text-xs font-extrabold text-telkomsel-500 hover:text-telkomsel-600 transition">
                            Lihat Semua Transaksi &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Produk Stok Tipis (Detail Mini) -->
            @if($lowStockProducts->count() > 0)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
                        <div>
                            <h3 class="font-extrabold text-lg text-gray-800 dark:text-white">Daftar Barang Stok Menipis</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Daftar barang gunung dengan kuantitas stok kurang dari 5 unit</p>
                        </div>
                        <span class="bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 text-xs px-2.5 py-1 rounded-full font-bold">
                            {{ $lowStockProducts->count() }} Peringatan
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="clean-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Lokasi</th>
                                    <th>Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $prod)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-850/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-500 dark:text-slate-400">{{ $prod->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-gray-900 dark:text-white">{{ $prod->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $prod->category->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <span class="inline-flex items-center gap-1.5 font-black text-rose-600 bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 rounded-lg border border-rose-100 dark:border-rose-900/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                                {{ $prod->stock }} unit
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $prod->storage_location }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-slate-800 dark:text-gray-300">
                                                {{ $prod->condition }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Script ChartJS untuk rendering grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('borrowingsChart').getContext('2d');
            
            // Periksa tema saat ini (gelap atau terang)
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#1e293b' : '#f1f5f9';
            const labelColor = isDark ? '#94a3b8' : '#64748b';

            // Pilihan palet warna merah Telkomsel dengan gradien
            let backgroundGradient = ctx.createLinearGradient(0, 0, 0, 300);
            backgroundGradient.addColorStop(0, 'rgba(230, 0, 0, 0.85)'); // Red
            backgroundGradient.addColorStop(1, 'rgba(230, 0, 0, 0.15)'); // Light Red

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: {!! json_encode($chartValues) !!},
                        backgroundColor: backgroundGradient,
                        borderColor: '#e60000',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: '#c40000',
                        hoverBorderColor: '#c40000',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legenda
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#0f172a' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#0f172a',
                            bodyColor: isDark ? '#cbd5e1' : '#475569',
                            borderColor: isDark ? '#1e293b' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Transaksi Peminjaman';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: labelColor,
                                font: {
                                    family: 'Outfit, Inter, sans-serif',
                                    size: 11
                                },
                                stepSize: 5
                            },
                            border: {
                                dash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: labelColor,
                                font: {
                                    family: 'Outfit, Inter, sans-serif',
                                    size: 11,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // Perbarui grafik saat tema berubah
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === "class") {
                        const newIsDark = document.documentElement.classList.contains('dark');
                        const newGridColor = newIsDark ? '#1e293b' : '#f1f5f9';
                        const newLabelColor = newIsDark ? '#94a3b8' : '#64748b';
                        
                        chart.options.scales.y.grid.color = newGridColor;
                        chart.options.scales.y.ticks.color = newLabelColor;
                        chart.options.scales.x.ticks.color = newLabelColor;
                        
                        chart.options.plugins.tooltip.backgroundColor = newIsDark ? '#0f172a' : '#ffffff';
                        chart.options.plugins.tooltip.titleColor = newIsDark ? '#ffffff' : '#0f172a';
                        chart.options.plugins.tooltip.bodyColor = newIsDark ? '#cbd5e1' : '#475569';
                        chart.options.plugins.tooltip.borderColor = newIsDark ? '#1e293b' : '#e2e8f0';

                        chart.update();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
</x-app-layout>
