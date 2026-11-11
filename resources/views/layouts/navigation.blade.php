<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Peminjaman</h1>
                <p class="text-sm text-gray-500 mt-0.5">Daftar riwayat dan status peminjaman barang Anda.</p>
            </div>
            <div>
                <a href="{{ route('loans.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/30 transition">
                    + Ajukan Peminjaman
                </a>
            </div>
        </div>

        <!-- Card Utama -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Tab Filter Status -->
            <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-4">
                <a href="{{ route('user.loans.index', ['status' => 'all']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') == 'all' || !request('status') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    all
                </a>
                <a href="{{ route('user.loans.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') == 'pending' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    pending
                </a>
                <a href="{{ route('user.loans.index', ['status' => 'dipinjam']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') == 'dipinjam' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    dipinjam
                </a>
                <a href="{{ route('user.loans.index', ['status' => 'dikembalikan']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') == 'dikembalikan' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    dikembalikan
                </a>
            </div>

            <!-- Bagian Search -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center text-sm text-gray-500 gap-2">
                    Menampilkan daftar peminjaman Anda
                </div>

                <div class="w-full sm:w-auto">
                    <form action="{{ route('user.loans.index') }}" method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." class="w-full sm:w-64 px-4 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50/50">
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-gray-50/70 text-gray-600 uppercase font-semibold tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">#</th>
                            <th class="px-6 py-3.5">Peminjam</th>
                            <th class="px-6 py-3.5">Barang</th>
                            <th class="px-6 py-3.5 text-center">Qty</th>
                            <th class="px-6 py-3.5">Tgl Pinjam</th>
                            <th class="px-6 py-3.5">Jatuh Tempo</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                        @forelse($loans ?? [] as $index => $loan)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ Auth::user()->name }} <span class="text-gray-400 font-normal">(saya)</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $loan->item->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold">{{ $loan->quantity ?? 1 }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($loan->status == 'pending')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wide">pending</span>
                                    @elseif($loan->status == 'dipinjam')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wide">dipinjam</span>
                                    @elseif($loan->status == 'dikembalikan')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wide">dikembalikan</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 uppercase tracking-wide">{{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($loan->status == 'pending')
                                        <span class="text-indigo-500 font-medium text-[11px] bg-indigo-50 px-2.5 py-1 rounded-md">⏳ Menunggu Approval</span>
                                    @elseif($loan->status == 'dipinjam')
                                        <form action="{{ route('user.loans.return', $loan->id) }}" method="POST" class="inline-block return-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="condition_on_return" value="baik">
                                            <button type="button" onclick="confirmReturn(this)" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition text-[11px]">
                                                🔄 Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-emerald-600 font-semibold text-[11px] bg-emerald-50 px-2.5 py-1 rounded-md">✔ Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    Belum ada data peminjaman barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="pt-2">
                @if(isset($loans) && method_exists($loans, 'links'))
                    {{ $loans->links() }}
                @endif
            </div>

        </div>

    </div>

    <!-- SweetAlert Konfirmasi Pengembalian -->
    <script>
        function confirmReturn(button) {
            Swal.fire({
                title: 'Kembalikan Barang?',
                text: "Ajukan pengembalian untuk barang ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
    </script>
</x-app-layout>