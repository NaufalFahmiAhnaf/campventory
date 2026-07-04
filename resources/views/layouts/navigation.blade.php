<!-- Sidebar Navigation -->
<aside 
    :class="[
        sidebarCollapsed ? 'w-[72px]' : 'w-[260px]',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
    ]"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white text-slate-700 transition-all duration-300 ease-in-out md:sticky md:top-0 md:h-screen md:z-30 border-r border-slate-200 shadow-xl shadow-slate-200/60 dark:bg-slate-900 dark:text-slate-100 dark:border-slate-800/60 dark:shadow-slate-950/20"
>
    <!-- Sidebar Header (Logo Klik Collapse) -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200 dark:border-slate-800/60 shrink-0">
        <button 
            @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebar-collapsed', sidebarCollapsed)"
            class="flex items-center gap-2.5 overflow-hidden transition-all duration-300 focus:outline-none hover:opacity-85 active:scale-95 group text-left w-full"
            title="Klik untuk buka/tutup menu"
        >
            <!-- Logo Icon -->
            <div class="bg-indigo-600 text-white font-black px-2 py-1.5 rounded-lg tracking-wider text-[10px] shadow-md shadow-indigo-600/20 shrink-0 uppercase transition-transform group-hover:scale-105">
                InLife
            </div>
            <!-- Logo Text -->
            <span 
                x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-250" 
                x-transition:enter-start="opacity-0 -translate-x-3" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="font-extrabold text-sm tracking-tight text-slate-900 dark:text-white whitespace-nowrap"
            >
                Camp<span class="text-indigo-400">Ventory</span>
            </span>
        </button>

        <!-- Close Button (Mobile Only) -->
        <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 focus:outline-none transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Section Label -->
    <div class="px-5 pt-4 pb-1 shrink-0" x-show="!sidebarCollapsed" x-transition:enter="transition duration-200">
        <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] opacity-80">Menu Utama</span>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto scrollbar-thin">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('dashboard') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Dashboard"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Dashboard</span>
        </a>

        <!-- Kategori (Admin & Staff) -->
        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
        <a href="{{ route('categories.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('categories.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Kategori Barang"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kategori Barang</span>
        </a>
        @endif

        <!-- Data Barang -->
        <a href="{{ route('products.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('products.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Data Barang"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Data Barang</span>
        </a>

        <!-- Peminjaman -->
        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
        <a href="{{ route('borrowings.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('borrowings.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Peminjaman"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Peminjaman</span>
        </a>
        @endif

        <!-- Laporan -->
        @if(Auth::user()->isAdmin() || Auth::user()->isStaff() || Auth::user()->isManager())
        <a href="{{ route('reports.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('reports.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Laporan"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Laporan</span>
        </a>
        @endif

        <!-- Log Aktivitas (Admin & Manager) -->
        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
        <a href="{{ route('activity-logs.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('activity-logs.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Log Aktivitas"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Log Aktivitas</span>
        </a>
        @endif

        <!-- Kelola User (Admin Only) -->
        @if(Auth::user()->isAdmin())
        <div class="pt-3 pb-1 shrink-0" x-show="!sidebarCollapsed" x-transition:enter="transition duration-200">
            <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] opacity-80">Pengaturan</span>
        </div>
        <a href="{{ route('users.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-[13px] transition-all duration-150 active:scale-98
                  {{ request()->routeIs('users.*') ? 'active-timbul' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800/40' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : ''"
           title="Kelola User"
        >
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kelola User</span>
        </a>
        @endif
    </nav>

    <!-- Sidebar Footer: Popover Menu Profil (Bawah Kiri) -->
    <div class="p-3 border-t border-slate-200 dark:border-slate-800/60 shrink-0 relative" x-data="{ popoverOpen: false }" @click.away="popoverOpen = false">
        <!-- Trigger Button -->
        <button 
            @click="popoverOpen = !popoverOpen"
            class="w-full flex items-center gap-2.5 px-2 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 transition duration-150 text-left focus:outline-none focus:ring-1 focus:ring-indigo-200 dark:focus:ring-slate-700 active:scale-98"
            title="Profil & Pengaturan Akun"
        >
            <!-- Avatar -->
            <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-500/30 flex items-center justify-center text-[10px] font-black text-indigo-700 dark:text-indigo-400 shrink-0 uppercase shadow-inner">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <!-- User Info -->
            <div x-show="!sidebarCollapsed" class="flex-1 min-w-0" x-transition:enter="transition duration-150">
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[9px] text-slate-500 dark:text-slate-500 truncate font-bold uppercase tracking-wider">{{ Auth::user()->role->name ?? 'User' }}</p>
            </div>
            <!-- Chevron up -->
            <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform" :class="popoverOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>

        <!-- Dropdown Popover Menu (Pops up) -->
        <div 
            x-show="popoverOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="transform opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="transform opacity-0 scale-95 translate-y-3"
            class="absolute bottom-16 left-3 right-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-xl shadow-2xl z-50 p-1.5 space-y-0.5"
            :class="sidebarCollapsed ? 'w-48 left-16 bottom-2' : ''"
            style="display: none;"
        >
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white transition">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil Saya
            </a>
            
            <hr class="border-slate-200 dark:border-slate-700/60 my-1">
            
            <form method="POST" action="{{ route('logout') }}" onsubmit="localStorage.setItem('dark-mode', 'false'); document.documentElement.classList.remove('dark');">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/20 dark:hover:text-rose-300 transition">
                    <svg class="w-4 h-4 text-rose-500 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Background Backdrop overlay (Mobile only) -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-45 md:hidden"
    x-transition:enter="transition-opacity ease-linear duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>
