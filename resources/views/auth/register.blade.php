<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Register — Inventaris Sekolah</title>

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
                
                <!-- Sisi Kiri: Branding & Informasi (Hidden di Mobile, Tampil di Desktop) -->
                <div class="hidden md:flex p-8 md:p-12 flex-col justify-between text-white bg-gradient-to-br from-blue-600 to-blue-700 relative overflow-hidden">
                    <!-- Aksen Dekoratif -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div>
                        <div class="inline-flex p-3.5 bg-white/15 rounded-2xl mb-8 backdrop-blur-md shadow-inner border border-white/20">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Pendaftaran Akun</h1>
                        <p class="text-blue-100 text-sm md:text-base mt-4 leading-relaxed">
                            Buat akun baru untuk mengakses sistem manajemen dan monitoring peminjaman barang sekolah secara transparan.
                        </p>
                    </div>

                    <!-- Catatan Cepat -->
                    <div class="mt-8 pt-6 border-t border-blue-500/50 text-xs text-blue-100 space-y-1">
                        <p class="font-bold text-white text-sm">Informasi:</p>
                        <p>Akun baru yang didaftarkan akan berstatus sebagai pengguna umum (User) peminjam barang.</p>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Register -->
                <div class="p-8 md:p-12 bg-white flex flex-col justify-center">
                    
                    <div class="mb-6">
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Buat Akun Baru</h2>
                        <p class="text-slate-500 text-sm mt-1">Lengkapi data diri Anda untuk mendaftar.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="Nama Lengkap Anda">
                            @error('name')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="nama@sekolah.com">
                            @error('email')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition text-sm bg-slate-50/50" 
                                placeholder="••••••••">
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Register & Link Login -->
                        <div class="pt-2 space-y-3">
                            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5 text-sm tracking-wide">
                                Daftar Sekarang
                            </button>

                            <div class="text-center text-xs text-slate-500">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </body>
</html>