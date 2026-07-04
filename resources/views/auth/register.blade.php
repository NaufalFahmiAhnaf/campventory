<x-guest-layout>
    <!-- Kop Header Form -->
    <div class="mb-6">
        <h2 class="font-extrabold text-2xl text-gray-800 dark:text-white leading-tight">
            Daftar Akun Baru 🚀
        </h2>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Buat akun staf baru Anda untuk mengelola inventaris barang gunung PT Telkomsel InLife.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </span>
                <input 
                    id="name" 
                    class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-950 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    placeholder="Nama Lengkap Staf" 
                />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs font-bold" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                </span>
                <input 
                    id="email" 
                    class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-950 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    placeholder="nama.staf@telkomsel.com"
                    oninput="this.value = this.value.toLowerCase()" 
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs font-bold" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Kata Sandi (Password)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </span>
                <input 
                    id="password" 
                    class="block w-full pl-10 pr-11 py-2.5 text-sm border border-gray-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-950 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                    :type="show ? 'text' : 'password'" 
                    name="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                />
                <!-- Eye Icon Toggle -->
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs font-bold" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-xs font-bold text-gray-550 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </span>
                <input 
                    id="password_confirmation" 
                    class="block w-full pl-10 pr-11 py-2.5 text-sm border border-gray-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-950 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                    :type="show ? 'text' : 'password'" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ulangi kata sandi"
                />
                <!-- Eye Icon Toggle -->
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs font-bold" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-red-650/15 hover:shadow-red-650/25 transition duration-150">
            Daftarkan Akun Baru
        </button>

        <!-- Footer Link Login -->
        <div class="text-center pt-2">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Sudah memiliki akun staf? 
                <a href="{{ route('login') }}" class="font-extrabold text-red-550 dark:text-red-400 hover:underline">
                    Masuk Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
