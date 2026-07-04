<header class="h-14 bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800/60 flex items-center justify-between px-6 sticky top-0 z-20 transition-colors duration-200">
    <!-- Kiri: Mobile Hamburger + Breadcrumbs -->
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Open Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden inline-flex items-center justify-center p-2.5 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-350 hover:bg-gray-100 dark:hover:bg-slate-850/80 focus:outline-none transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Breadcrumbs -->
        <div class="hidden sm:flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 font-medium">
            <svg class="w-4 h-4 text-gray-450 dark:text-gray-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>/</span>
            <span class="font-bold text-gray-700 dark:text-gray-200 capitalize tracking-wide">{{ Request::segment(1) ? str_replace('-', ' ', Request::segment(1)) : 'Dashboard' }}</span>
        </div>
    </div>

    <!-- Kanan: Mode Gelap (Tanpa Profil Dropdown) -->
    <div class="flex items-center gap-2">
        <!-- Dark Mode Toggle -->
        <button id="theme-toggle" type="button" class="p-2 rounded-xl text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none transition duration-200">
            <!-- Dark Icon -->
            <svg id="theme-toggle-dark-icon" class="hidden w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <!-- Light Icon -->
            <svg id="theme-toggle-light-icon" class="hidden w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</header>
