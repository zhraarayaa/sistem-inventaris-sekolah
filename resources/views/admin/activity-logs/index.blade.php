<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Card Utama -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Bar Atas: Subtitle -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm font-semibold text-blue-600">
                    Riwayat Aktivitas Sistem
                </div>
            </div>

            <!-- BAGIAN FILTER & LIVE SEARCH -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <!-- Dropdown Tampilkan Data -->
                <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="perPageForm" class="flex items-center gap-2">
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
                                <th class="px-6 py-3.5">User</th>
                                <th class="px-6 py-3.5">Aksi</th>
                                <th class="px-6 py-3.5">Deskripsi</th>
                                <th class="px-6 py-3.5">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse($activityLogs ?? [] as $index => $log)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $activityLogs->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $log->user->name ?? 'System / Tamu' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-rose-600">
                                        {{ $log->action }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada riwayat aktivitas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- BAGIAN BAWAH: Render Paginator & Informasi Total Data -->
                <div class="flex flex-col sm:flex-row items-center justify-between pt-2 text-xs text-gray-500 gap-4">
                    <div>
                        @if(isset($activityLogs) && $activityLogs->total() > 0)
                            Menampilkan {{ $activityLogs->firstItem() }} - {{ $activityLogs->lastItem() }} dari {{ $activityLogs->total() }} data
                        @else
                            Menampilkan 0 data
                        @endif
                    </div>

                    <div>
                        @if(isset($activityLogs) && method_exists($activityLogs, 'links'))
                            {{ $activityLogs->appends(['perPage' => request('perPage'), 'search' => request('search')])->onEachSide(1)->links() }}
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Script Live Search Activity Log -->
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