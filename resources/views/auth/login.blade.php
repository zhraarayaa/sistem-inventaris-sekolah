<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login — Inventaris Sekolah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-gradient-to-br from-blue-100 via-sky-50 to-blue-200 min-h-screen">
        <div class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 lg:p-8">
            
            <!-- Card Container Utama: 2 Kolom Berdampingan -->
            <div class="max-w-5xl w-full bg-white/90 backdrop-blur-2xl border border-white/80 rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
                
                <!-- Sisi Kiri: Branding & Informasi (Hidden di Mobile) -->
                <div class="hidden md:flex p-8 md:p-12 flex-col justify-between text-white bg-gradient-to-br from-blue-600 to-blue-700 relative overflow-hidden">
                    <!-- Aksen Dekoratif -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div>
                        <div class="inline-flex p-3.5 bg-white/15 rounded-2xl mb-8 backdrop-blur-md shadow-inner border border-white/20">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Inventaris Sekolah</h1>
                        <p class="text-blue-100 text-sm md:text-base mt-4 leading-relaxed">
                            Sistem manajemen dan monitoring peminjaman barang sekolah terpadu yang cepat, transparan, dan profesional.
                        </p>
                    </div>

                    <!-- Info Kredensial Cepat untuk Demo -->
                    <div class="mt-8 pt-6 border-t border-blue-500/50 text-xs text-blue-100 space-y-1.5">
                        <p class="font-bold text-white text-sm">Kredensial Demo:</p>
                        <p>Admin: <span class="font-mono bg-blue-800/40 px-2 py-0.5 rounded text-white font-medium">admin@sekolah.com</span></p>
                        <p>User: <span class="font-mono bg-blue-800/40 px-2 py-0.5 rounded text-white font-medium">aya@siswa.com</span></p>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Login -->
                <div class="p-8 md:p-12 bg-white flex flex-col justify-center">
                    
                    <div class="mb-6">
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Selamat Datang</h2>
                        <p class="text-slate-500 text-sm mt-1">Silakan masuk menggunakan akun terdaftar Anda.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 text-sm font-medium text-emerald-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="admin@sekolah.com">
                            @error('email')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-sm">
                            <label for="remember_me" class="flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4">
                                <span class="ml-2 text-slate-600 text-xs font-medium">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5 text-sm tracking-wide mt-2">
                            Masuk ke Sistem
                        </button>

                        <!-- Tautan ke Halaman Register Bawaan Breeze -->
                        @if (Route::has('register'))
                            <div class="text-center pt-3 text-xs text-slate-500">
                                Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar sekarang</a>
                            </div>
                        @endif
                    </form>

                </div>

            </div>
        </div>
    </body>
</html>