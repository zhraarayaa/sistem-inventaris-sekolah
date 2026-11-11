<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(auth()->user()->role === 'admin')
            <!-- ================= DASHBOARD ADMIN ================= -->
            
            <!-- Banner Sambutan Admin -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 rounded-3xl p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold tracking-wide shadow-inner">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Status: Administrator Sistem
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                        Halo, {{ Auth::user()->name }}! Selamat datang kembali.
                    </h2>
                    <p class="text-blue-100 text-xs sm:text-sm max-w-xl leading-relaxed">
                        Kelola data inventaris, verifikasi peminjaman barang, dan pantau seluruh aktivitas pengguna sekolah dari panel admin ini.
                    </p>
                </div>

                <div class="relative z-10 shrink-0">
                    <a href="{{ route('admin.items.index') }}" style="color: #1d4ed8 !important; background-color: #ffffff !important;" class="px-5 py-2.5 font-extrabold rounded-2xl shadow-md transition duration-200 text-xs inline-flex items-center gap-2">
                        Kelola Barang
                    </a>
                </div>
            </div>

            <!-- 4 Kartu Statistik Admin -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Total Barang -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-blue-600">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Barang</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalItems ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>

                <!-- Total Stok -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-emerald-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Stok</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalStock ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-amber-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sedang Dipinjam</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $activeLoans ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-2xl text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                </div>

                <!-- Pending / Diajukan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-rose-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pending / Diajukan</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $pendingLoans ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-2xl text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

            </div>

            <!-- Grafik & Statistik Admin -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Tren Peminjaman -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 space-y-4 lg:col-span-2">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tren Peminjaman Barang (Tahun {{ date('Y') }})</span>
                    <div class="relative h-72">
                        <canvas id="loanTrendChart"></canvas>
                    </div>
                </div>

                <!-- Top 5 Barang Favorit -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 space-y-4">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Top 5 Barang Favorit</span>
                    <div class="divide-y divide-gray-100 text-xs">
                        @forelse($topItems ?? [] as $item)
                            <div class="py-3.5 flex justify-between items-center">
                                <span class="font-semibold text-gray-800 truncate pr-2">{{ $item->name }}</span>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 font-bold rounded-xl shrink-0">{{ $item->loans_count }}x dipinjam</span>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-8">Belum ada data peminjaman.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Dua Tabel Berdampingan di Bawah -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Kiri: Data Barang Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 space-y-4">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Data Barang Terbaru</span>

                    <div class="overflow-x-auto rounded-2xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Nama Barang</th>
                                    <th class="px-4 py-3">Stok</th>
                                    <th class="px-4 py-3">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                                @forelse($latestItems ?? [] as $item)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->name }}</td>
                                        <td class="px-4 py-3 font-bold">{{ $item->stock }}</td>
                                        <td class="px-4 py-3 capitalize text-gray-500">{{ $item->condition }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kanan: Status Peminjaman Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 space-y-4">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Status Peminjaman Terbaru</span>

                    <div class="overflow-x-auto rounded-2xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Peminjam</th>
                                    <th class="px-4 py-3">Barang</th>
                                    <th class="px-4 py-3 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                                @forelse($latestLoans ?? [] as $loan)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $loan->user->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $loan->item->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            @if($loan->status == 'dipinjam')
                                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase">Dipinjam</span>
                                            @elseif($loan->status == 'pending')
                                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-200 uppercase">Pending</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase">Dikembalikan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        @else
            <!-- ================= DASHBOARD USER ================= -->
            
            <!-- Banner Sambutan User -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 rounded-3xl p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold tracking-wide shadow-inner">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Status: Pengguna Umum (User)
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                        Halo, {{ Auth::user()->name }}! Selamat datang kembali.
                    </h2>
                    <p class="text-blue-100 text-xs sm:text-sm max-w-xl leading-relaxed">
                        Kelola pengajuan peminjaman barang dan pantau riwayat inventaris sekolah Anda dengan cepat dan transparan.
                    </p>
                </div>

                <div class="relative z-10 shrink-0">
                    <a href="{{ route('loans.index') }}" style="color: #1d4ed8 !important; background-color: #ffffff !important;" class="px-5 py-2.5 font-extrabold rounded-2xl shadow-md transition duration-200 text-xs inline-flex items-center gap-2">
                        Cek Peminjaman
                    </a>
                </div>
            </div>

            <!-- 4 Kartu Statistik User -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-blue-600">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Barang</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalItems ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-emerald-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Stok</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalStock ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-amber-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sedang Dipinjam</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $activeLoans ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-2xl text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-5 flex items-center justify-between border-l-4 border-l-rose-500">
                    <div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Terlambat</div>
                        <div class="text-2xl font-extrabold text-gray-800 mt-1">{{ $lateLoans ?? 0 }}</div>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-2xl text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Peminjaman User -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 space-y-4">
                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Riwayat Peminjaman Saya (5 Terakhir)
                </div>
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse($recentLoans ?? [] as $loan)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-3.5 font-semibold text-gray-800">
                                        {{ $loan->item->name ?? '-' }} 
                                        <span class="text-gray-400 font-normal">({{ $loan->quantity }} pcs)</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        @if($loan->status == 'dikembalikan')
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase">Dikembalikan</span>
                                        @elseif($loan->status == 'dipinjam')
                                            @if(\Carbon\Carbon::parse($loan->due_date)->isPast())
                                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200 uppercase">Terlambat</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase">Dipinjam</span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-200 uppercase">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center text-gray-400">
                                        Belum ada riwayat peminjaman barang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- CDN Chart.js untuk Grafik Statistik Admin -->
    @if(auth()->user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('loanTrendChart').getContext('2d');
            const loanTrendChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: @json($loanChartData ?? []),
                        backgroundColor: 'rgba(37, 99, 235, 0.8)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endif
</x-app-layout>