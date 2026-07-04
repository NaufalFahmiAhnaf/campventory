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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Dark Mode Script -->
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-gray-100 min-h-screen overflow-x-hidden">
        
        <!-- Toast Notification Global -->
        <div 
            x-data="{
                show: false,
                message: '',
                type: 'success',
                trigger(msg, type = 'success') {
                    this.message = msg;
                    this.type = type;
                    this.show = true;
                    // Reset progress bar animation
                    const progress = this.$refs.progressBar;
                    if (progress) {
                        progress.style.animation = 'none';
                        progress.offsetHeight; // trigger reflow
                        progress.style.animation = 'progressCountdown 4s linear forwards';
                    }
                    setTimeout(() => {
                        this.show = false;
                    }, 4000);
                },
                init() {
                    @if(session('success'))
                        this.trigger('{{ session('success') }}', 'success');
                    @endif
                    @if(session('error'))
                        this.trigger('{{ session('error') }}', 'error');
                    @endif
                }
            }"
            @show-toast.window="trigger($event.detail.message, $event.detail.type)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border rounded-2xl shadow-xl overflow-hidden pointer-events-auto"
            :class="type === 'success' ? 'border-emerald-500/30 dark:border-emerald-500/20' : 'border-rose-500/30 dark:border-rose-500/20'"
            style="display: none;"
        >
            <div class="p-4 flex items-start gap-3">
                <!-- Icon -->
                <div class="shrink-0">
                    <template x-if="type === 'success'">
                        <span class="inline-flex p-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </template>
                    <template x-if="type === 'error'">
                        <span class="inline-flex p-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                    </template>
                </div>
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500" x-text="type === 'success' ? 'Berhasil' : 'Kesalahan'"></p>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mt-0.5 leading-relaxed" x-text="message"></p>
                </div>
                <!-- Close Button -->
                <button @click="show = false" class="shrink-0 text-slate-400 hover:text-slate-650 dark:hover:text-slate-350 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Progress bar -->
            <div class="h-[3px] w-full bg-slate-100 dark:bg-slate-800/60">
                <div 
                    x-ref="progressBar"
                    class="h-full"
                    :class="type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                ></div>
            </div>
        </div>

        <!-- Root Div dengan state Alpine untuk sidebar -->
        <div x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true' 
        }" class="min-h-screen flex bg-gray-50 dark:bg-slate-950">
            
            <!-- Left Side Collapsible Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Right Side Main Content Wrapper (Lebar Penuh) -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-y-auto">
                
                <!-- Top Header Bar -->
                @include('layouts.header')

                <!-- Page Header (jika ada) -->
                @isset($header)
                    <div class="bg-white dark:bg-slate-900 border-b border-gray-150 dark:border-slate-800/80 px-6 py-5 transition-colors duration-200">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Page Content Area (Lebar Penuh) -->
                <main class="flex-1 p-6 animate-page-entrance">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Dark Mode Toggle Logic -->
        <script>
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Atur status ikon awal sesuai tema saat ini
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon?.classList.remove('hidden');
            } else {
                themeToggleDarkIcon?.classList.remove('hidden');
            }

            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('dark-mode', 'false');
                    
                    themeToggleLightIcon?.classList.add('hidden');
                    themeToggleDarkIcon?.classList.remove('hidden');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('dark-mode', 'true');
                    
                    themeToggleDarkIcon?.classList.add('hidden');
                    themeToggleLightIcon?.classList.remove('hidden');
                }
            }

            themeToggleBtn?.addEventListener('click', toggleTheme);
        </script>
        @stack('scripts')
    </body>
</html>
