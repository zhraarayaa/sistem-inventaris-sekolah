<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="space-y-6">
        
        <!-- Banner Sambutan Profesional (Baru) -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-blue-100 text-sm mt-1">Sistem Pengelolaan dan Monitoring Inventaris Sekolah Terpadu.</p>
            </div>
            <div class="hidden md:block bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm text-sm border border-white/20">
                Role: <span class="font-semibold uppercase tracking-wider">{{ auth()->user()->role }}</span>
            </div>
        </div>

        <!-- Baris Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Total Barang -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Barang</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Item::count() }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-500 font-bold">📦</div>
            </div>

            <!-- Total Stok -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Stok</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Item::sum('stock') }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-500 font-bold">📊</div>
            </div>

            <!-- Sedang Dipinjam -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Dipinjam</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Loan::where('status', 'approved')->count() }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg text-yellow-500 font-bold">🔄</div>
            </div>

            <!-- Menunggu Persetujuan -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending / Diajukan</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Loan::where('status', 'pending')->count() }}</h3>
                </div>
                <div class="p-3 bg-red-50 rounded-lg text-red-500 font-bold">⏳</div>
            </div>

        </div>

        <!-- Tabel Informasi Tambahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            
            <!-- Barang Terbaru -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-gray-800 mb-4">📦 Data Barang Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach(\App\Models\Item::latest()->take(5)->get() as $item)
                                <tr>
                                    <td class="py-2 font-medium text-gray-800">{{ $item->name }}</td>
                                    <td class="py-2 text-gray-600">{{ $item->stock }}</td>
                                    <td class="py-2 text-gray-600">{{ ucfirst($item->condition) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Aktivitas Peminjaman Terbaru -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-gray-800 mb-4">🔄 Status Peminjaman Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                                <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach(\App\Models\Loan::with(['user', 'item'])->latest()->take(5)->get() as $loan)
                                <tr>
                                    <td class="py-2 font-medium text-gray-800">{{ $loan->user->name ?? '-' }}</td>
                                    <td class="py-2 text-gray-600">{{ $loan->item->name ?? '-' }}</td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $loan->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>