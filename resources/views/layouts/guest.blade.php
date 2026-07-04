<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CampVentory') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script>
            if (localStorage.getItem('dark-mode') === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-slate-950 min-h-screen">
        <div class="min-h-screen flex flex-col md:flex-row">
            
            <!-- Sisi Kiri: Branding & Informasi Korporat (Hanya tampil di MD ke atas) -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-slate-900 via-slate-950 to-red-950 text-white flex-col justify-between p-12 relative overflow-hidden animate-brand-entrance">
                <!-- Ornamen Latar Belakang Lingkaran Bercahaya -->
                <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-red-650/15 blur-3xl"></div>
                <div class="absolute -left-16 -bottom-16 w-96 h-96 rounded-full bg-slate-800/20 blur-3xl"></div>

                <!-- Bagian Atas: Logo -->
                <div class="flex items-center gap-3 relative z-10">
                    <div class="bg-red-600 text-white font-black px-3.5 py-2 rounded-xl tracking-wider text-sm shadow-md shadow-red-600/30">
                        INLIFE
                    </div>
                    <span class="font-extrabold text-xl tracking-tight text-white">
                        Camp<span class="text-red-500">Ventory</span>
                    </span>
                </div>

                <!-- Bagian Tengah: Teks Pengantar Premium -->
                <div class="my-auto max-w-lg relative z-10 space-y-6">
                    <h1 class="font-black text-4xl lg:text-5xl leading-tight font-display tracking-tight text-white">
                        Kelola Perlengkapan Gunung Lebih <span class="text-red-500 bg-gradient-to-r from-red-500 to-amber-500 bg-clip-text text-transparent">Cepat & Aman</span>
                    </h1>
                    <p class="text-base text-slate-400 font-medium leading-relaxed">
                        Sistem Inventarisasi Modern PT Telkomsel InLife untuk meminimalisir kehilangan aset gunung, duplikasi pencatatan stok, dan menyederhanakan laporan dengan akurasi tinggi.
                    </p>
                </div>

                <!-- Bagian Bawah: Footer Halaman -->
                <div class="relative z-10 text-xs text-slate-500 font-semibold">
                    &copy; 2026 PT Telkomsel InLife. Hak Cipta Dilindungi Undang-Undang.
                </div>
            </div>

            <!-- Sisi Kanan: Formulir Login / Registrasi (Tampil penuh di mobile) -->
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-50 dark:bg-slate-950 relative">
                <!-- Tombol Kembali / Identitas Mobile (Hanya tampil di Mobile) -->
                <div class="flex md:hidden items-center gap-2 mb-8 self-start">
                    <div class="bg-red-600 text-white font-black px-2.5 py-1.5 rounded-lg tracking-wider text-xs shadow-md">
                        INLIFE
                    </div>
                    <span class="font-extrabold text-base tracking-tight text-gray-800 dark:text-white">
                        Camp<span class="text-red-500">Ventory</span>
                    </span>
                </div>

                <!-- Card container Glassmorphism -->
                <div class="w-full sm:max-w-md bg-white dark:bg-slate-900 border border-gray-150 dark:border-slate-800/80 shadow-xl rounded-3xl p-8 transition duration-200 animate-login-entrance">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
