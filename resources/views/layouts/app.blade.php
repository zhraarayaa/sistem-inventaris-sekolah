<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Inventaris Sekolah') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- CSS Paksa Sembunyikan Tombol Hamburger di Desktop -->
        <style>
            @media (min-width: 768px) {
                header button:has(svg path[d*="M4 6h16M4 12h16M4 18h16"]) {
                    display: none !important;
                }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-gradient-to-br from-blue-100 via-sky-50 to-blue-200 min-h-screen text-sm" x-data="{ mobileSidebarOpen: false, desktopSidebarOpen: true }">
        <div class="min-h-screen flex">
            
            <!-- Sidebar Samping Kiri (Desktop) - Menggunakan h-screen sticky agar tombol bawah konsisten -->
            <aside :class="desktopSidebarOpen ? 'w-64' : 'w-20'" class="bg-blue-600 text-white h-screen sticky top-0 flex flex-col justify-between shadow-xl z-20 shrink-0 transition-all duration-300">
                <div class="overflow-y-auto overflow-x-hidden flex-1">
                    <!-- Logo / Judul Sidebar -->
                    <div class="h-16 px-6 flex items-center space-x-3 border-b border-blue-500/60 bg-blue-700/40 overflow-hidden shrink-0">
                        <svg class="w-7 h-7 text-white drop-shadow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span x-show="desktopSidebarOpen" class="text-base font-extrabold tracking-wider drop-shadow whitespace-nowrap">INVENTARIS</span>
                    </div>

                    <!-- Menu Navigasi Sidebar -->
                    <nav class="mt-6 px-3 space-y-1.5 font-medium text-xs">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                            <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Dashboard</span>
                        </a>
                        
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.items.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.items.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Barang</span>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Kategori</span>
                            </a>
                        @else
                            <a href="{{ route('user.items.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('user.items.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Barang</span>
                            </a>
                        @endif

                        <a href="{{ route('loans.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('loans.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                            <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Peminjaman</span>
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.return-logs.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.return-logs.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-4-4m0 0l4-4m-4 4h12a4 4 0 014 4v1"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Pengembalian</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Manajemen User</span>
                            </a>
                            <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-blue-700 text-white font-semibold shadow-md' : 'text-blue-100 hover:bg-blue-500/50' }}">
                                <svg class="w-5 h-5 shrink-0" :class="desktopSidebarOpen ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <span x-show="desktopSidebarOpen" class="whitespace-nowrap">Activity Log</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Tombol Panah Bawah ala Gamelab yang Konsisten Menempel di Bawah -->
                <div class="p-4 flex justify-center border-t border-blue-500/40 bg-blue-600 shrink-0">
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="w-10 h-10 rounded-full bg-blue-700/60 hover:bg-blue-700 text-white flex items-center justify-center transition shadow-md focus:outline-none">
                        <svg class="w-5 h-5 transition-transform duration-300" :class="desktopSidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                </div>
            </aside>

            <!-- Sidebar Mobile Drawer (Hanya muncul saat ukuran layar kecil / mobile) -->
            <div x-show="mobileSidebarOpen" class="fixed inset-0 z-50 flex md:hidden" x-cloak>
                <div @click="mobileSidebarOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                <aside class="relative w-64 bg-blue-600 text-white flex flex-col justify-between shadow-2xl z-10 h-full">
                    <div>
                        <div class="h-16 px-6 flex items-center justify-between border-b border-blue-500/60 bg-blue-700/40">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-white drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <span class="text-base font-extrabold tracking-wider drop-shadow">INVENTARIS</span>
                            </div>
                            <button @click="mobileSidebarOpen = false" class="text-white hover:text-slate-200 font-bold text-2xl leading-none">×</button>
                        </div>
                        <nav class="mt-6 px-4 space-y-1.5 font-medium text-xs">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Dashboard</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.items.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.items.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Barang</a>
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Kategori</a>
                            @else
                                <a href="{{ route('user.items.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('user.items.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Barang</a>
                            @endif
                            <a href="{{ route('loans.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('loans.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Peminjaman</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.return-logs.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.return-logs.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Pengembalian</a>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Manajemen User</a>
                                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.activity-logs.*') ? 'bg-blue-700 text-white font-semibold' : 'text-blue-100 hover:bg-blue-500/50' }}">Activity Log</a>
                            @endif
                        </nav>
                    </div>
                </aside>
            </div>

            <!-- Konten Utama di Kanan -->
            <div class="flex-1 flex flex-col min-w-0 bg-transparent">
                
                <!-- Top Navbar -->
                <header class="bg-white/90 backdrop-blur-md shadow-sm h-16 flex items-center justify-between px-4 sm:px-8 relative z-30 border-b border-slate-200/80 sticky top-0">
                    
                    <!-- Tombol Hamburger (Hanya muncul jika layar md ke bawah / mode mobile) -->
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" class="md:hidden p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 focus:outline-none transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>

                    <!-- Judul Halaman di Tengah -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 text-center w-48 sm:w-auto">
                        <h1 class="text-sm sm:text-base md:text-lg font-extrabold text-slate-800 tracking-tight truncate">
                            @if(request()->routeIs('dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('*items*'))
                                Peminjaman & Manajemen Barang
                            @elseif(request()->routeIs('*categories*'))
                                Kategori Barang
                            @elseif(request()->routeIs('*loans*'))
                                Peminjaman Barang
                            @elseif(request()->routeIs('*return-logs*'))
                                Log Pengembalian
                            @elseif(request()->routeIs('*users*'))
                                Manajemen User
                            @elseif(request()->routeIs('*activity-logs*'))
                                Activity Log
                            @else
                                Sistem Inventaris
                            @endif
                        </h1>
                    </div>

                    <!-- Dropdown Profil & Logout -->
                    <div class="relative z-50">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 sm:space-x-3 focus:outline-none bg-blue-50/80 hover:bg-blue-100/80 px-2.5 sm:px-3 py-1.5 rounded-xl transition border border-blue-100 shadow-sm">
                            <span class="text-xs font-semibold text-slate-700 hidden sm:inline">{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-blue-100 py-2">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 md:p-8">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white/60 backdrop-blur-sm text-center py-4 text-xs text-slate-500 border-t border-blue-100/60">
                    © 2026 Sistem Inventaris Sekolah — Demo
                </footer>
            </div>

        </div>

        <!-- SweetAlert2 & Script -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('profileDropdown');
                dropdown.classList.toggle('hidden');
            }

            window.addEventListener('click', function(e) {
                const button = document.querySelector('button[onclick="toggleDropdown()"]');
                const dropdown = document.getElementById('profileDropdown');
                if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            function confirmLogout() {
                Swal.fire({
                    title: 'Keluar Aplikasi?',
                    text: "Anda akan keluar dari sesi ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                })
            }
        </script>
    </body>
</html>