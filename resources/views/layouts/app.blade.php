<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            
            <!-- Sidebar Samping Kiri -->
            <aside class="w-64 bg-blue-600 text-white flex flex-col justify-between hidden md:flex shadow-lg">
                <div>
                    <!-- Logo / Judul Sidebar -->
                    <div class="px-6 py-5 flex items-center space-x-3 border-b border-blue-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="text-xl font-bold tracking-wider">INVENTARIS</span>
                    </div>

                    <!-- Menu Navigasi Sidebar -->
                    <nav class="mt-6 px-4 space-y-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                            📊 Dashboard
                        </a>
                        
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.items.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.items.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                📦 Barang
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                📁 Kategori
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                👤 Manajemen User
                            </a>
                        @else
                            <!-- Menu Khusus User / Siswa -->
                            <a href="{{ route('user.items.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('user.items.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                📦 Data Barang
                            </a>
                        @endif

                        <a href="{{ route('loans.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('loans.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                            🔄 Peminjaman
                        </a>
                    </nav>
                </div>

                <!-- Tombol Logout di Bawah Sidebar -->
                <div class="p-4 border-t border-blue-500">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-blue-100 hover:bg-blue-500/50 rounded-xl text-sm font-medium transition">
                            🚪 Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Konten Utama di Kanan -->
            <div class="flex-1 flex flex-col min-w-0">
                
                <!-- Top Navbar -->
                <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                    <h1 class="text-xl font-bold text-gray-800">
                        @isset($header)
                            {{ $header }}
                        @else
                            Dashboard
                        @endisset
                    </h1>

                    <!-- Profil User di Pojok Kanan Atas -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                    </div>
                </header>

                <!-- Page Content / Isi Halaman -->
                <main class="flex-1 p-8">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white text-center py-4 text-xs text-gray-500 border-t">
                    &copy; 2026 Sistem Inventaris Sekolah — Demo
                </footer>
            </div>

        </div>
    </body>
</html>