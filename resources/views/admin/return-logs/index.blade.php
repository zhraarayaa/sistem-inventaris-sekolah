<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Card Utama -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Bar Atas: Subtitle -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm font-semibold text-blue-600">
                    Riwayat Pengembalian Barang
                </div>
            </div>

            <!-- BAGIAN FILTER & LIVE SEARCH -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <!-- Dropdown Tampilkan Data -->
                <form method="GET" action="{{ route('admin.return-logs.index') }}" id="perPageForm" class="flex items-center gap-2">
                    <span>Tampilkan</span>
                    <select name="perPage" onchange="document.getElementById('perPageForm').submit()" class="border-gray-200 rounded-lg text-xs px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50">
                        <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <span>data</span>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                </form>

                <!-- Live Search Bar -->
                <div class="w-full sm:w-auto flex items-center gap-2">
                    <span>Cari:</span>
                    <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="" class="w-full sm:w-64 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50/50">
                </div>
            </div>

            <!-- WRAPPER KONTEN TABEL & PAGINATION -->
            <div id="tableContentWrapper" class="space-y-6">
                <!-- TABEL DATA -->
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50/70 text-gray-600 uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">#</th>
                                <th class="px-6 py-3.5">Peminjam</th>
                                <th class="px-6 py-3.5">Barang</th>
                                <th class="px-6 py-3.5">Tgl Kembali</th>
                                <th class="px-6 py-3.5">Kondisi</th>
                                <th class="px-6 py-3.5">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse($returnLogs ?? [] as $index => $log)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        #{{ $returnLogs->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $log->loan->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 font-medium">
                                        {{ $log->loan->item->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $log->returned_at ? \Carbon\Carbon::parse($log->returned_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(strtolower($log->condition_on_return) == 'baik')
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-500 text-white uppercase">baik</span>
                                        @elseif(strtolower($log->condition_on_return) == 'rusak ringan')
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-amber-500 text-white uppercase">rusak ringan</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-rose-500 text-white uppercase">rusak berat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $log->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada riwayat pengembalian barang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- BAGIAN BAWAH: Render Paginator & Informasi Total Data -->
                <div class="flex flex-col sm:flex-row items-center justify-between pt-2 text-xs text-gray-500 gap-4">
                    <div>
                        @if(isset($returnLogs) && $returnLogs->total() > 0)
                            Menampilkan {{ $returnLogs->firstItem() }} - {{ $returnLogs->lastItem() }} dari {{ $returnLogs->total() }} data
                        @else
                            Menampilkan 0 data
                        @endif
                    </div>

                    <div>
                        @if(isset($returnLogs) && method_exists($returnLogs, 'links'))
                            {{ $returnLogs->appends(['perPage' => request('perPage'), 'search' => request('search')])->links() }}
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Script Live Search Pengembalian -->
    <script>
        document.getElementById('liveSearchInput').addEventListener('input', function() {
            let searchValue = this.value;
            let perPage = document.querySelector('select[name="perPage"]').value;
            
            let url = new URL(window.location.href);
            url.searchParams.set('search', searchValue);
            url.searchParams.set('perPage', perPage);
            url.searchParams.set('page', 1);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                document.getElementById('tableContentWrapper').innerHTML = doc.getElementById('tableContentWrapper').innerHTML;
                
                window.history.pushState({}, '', url);
            });
        });
    </script>
</x-app-layout>