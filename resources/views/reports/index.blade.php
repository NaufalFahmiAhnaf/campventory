<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
            Pusat Laporan & Ekspor Data
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unduh laporan data inventaris dan riwayat peminjaman aset dalam format PDF atau Excel (CSV).</p>
    </x-slot>
    
    <div class="py-6">
        <div class="w-full space-y-8">
 
             {{-- ===================== SEKSI 1: Laporan Stok Barang ===================== --}}
             <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                 <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-850">
                     <div class="flex items-center gap-3">
                         <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                         </div>
                         <div>
                             <h3 class="font-extrabold text-lg text-gray-800 dark:text-white">Laporan Data Stok Barang</h3>
                             <p class="text-xs text-gray-400 dark:text-gray-500">Ekspor seluruh data master barang inventaris beserta filter rentang tanggal dan kategori.</p>
                         </div>
                     </div>
                 </div>
                 <div class="p-6">
                     <form id="productReportForm" class="space-y-5">
                         <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                             <!-- Filter Tanggal Mulai -->
                             <div>
                                 <label for="prod_start_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Tanggal Ditambahkan (Mulai)</label>
                                 <input type="date" name="start_date" id="prod_start_date" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                             </div>
                             <!-- Filter Tanggal Akhir -->
                             <div>
                                 <label for="prod_end_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Tanggal Ditambahkan (Akhir)</label>
                                 <input type="date" name="end_date" id="prod_end_date" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                             </div>
                             <!-- Filter Kategori -->
                             <div>
                                 <label for="prod_category_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Kategori Barang</label>
                                 <select name="category_id" id="prod_category_id" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                     <option value="">Semua Kategori</option>
                                     @foreach($categories as $cat)
                                         <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         
                         <div class="flex flex-wrap gap-3">
                             <button type="button" onclick="downloadProductReport('pdf')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 hover:shadow-lg">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                 Unduh PDF
                             </button>
                             <button type="button" onclick="downloadProductReport('excel')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 hover:shadow-lg">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                 Unduh Excel (CSV)
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
  
             {{-- ===================== SEKSI 2: Laporan Peminjaman ===================== --}}
             <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-150 dark:border-slate-800 shadow-sm overflow-hidden">
                 <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-850">
                     <div class="flex items-center gap-3">
                         <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                         </div>
                         <div>
                             <h3 class="font-extrabold text-lg text-gray-800 dark:text-white">Laporan Transaksi Peminjaman</h3>
                             <p class="text-xs text-gray-400 dark:text-gray-500">Ekspor riwayat transaksi peminjaman aset dengan filter rentang tanggal dan status.</p>
                         </div>
                     </div>
                 </div>
                 <div class="p-6">
                     <form id="borrowingReportForm">
                         <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                             <!-- Filter Tanggal Mulai -->
                             <div>
                                 <label for="start_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Tanggal Mulai</label>
                                 <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                             </div>
                             <!-- Filter Tanggal Akhir -->
                             <div>
                                 <label for="end_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Tanggal Akhir</label>
                                 <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                             </div>
                             <!-- Filter Status -->
                             <div>
                                 <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Status</label>
                                 <select name="status" id="status" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-slate-750 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                     <option value="">Semua Status</option>
                                     <option value="Dipinjam">Dipinjam</option>
                                     <option value="Dikembalikan">Dikembalikan</option>
                                 </select>
                             </div>
                         </div>
                         <div class="flex flex-wrap gap-3">
                             <button type="button" onclick="downloadReport('pdf')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 hover:shadow-lg">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                 Unduh PDF
                             </button>
                             <button type="button" onclick="downloadReport('excel')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 hover:shadow-lg">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                 Unduh Excel (CSV)
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
  
         </div>
     </div>
  
     <script>
         function downloadProductReport(format) {
             const startDate = document.getElementById('prod_start_date').value;
             const endDate = document.getElementById('prod_end_date').value;
             const categoryId = document.getElementById('prod_category_id').value;
             
             let url = '{{ url("/reports/products") }}/' + format + '?';
             const params = [];
             if (startDate) params.push('start_date=' + startDate);
             if (endDate) params.push('end_date=' + endDate);
             if (categoryId) params.push('category_id=' + categoryId);
             
             url += params.join('&');
             window.location.href = url;
         }

         function downloadReport(format) {
             const startDate = document.getElementById('start_date').value;
             const endDate = document.getElementById('end_date').value;
             const status = document.getElementById('status').value;
             
             let url = '{{ url("/reports/borrowings") }}/' + format + '?';
             const params = [];
             if (startDate) params.push('start_date=' + startDate);
             if (endDate) params.push('end_date=' + endDate);
             if (status) params.push('status=' + status);
             
             url += params.join('&');
             window.location.href = url;
         }
     </script>
</x-app-layout>
