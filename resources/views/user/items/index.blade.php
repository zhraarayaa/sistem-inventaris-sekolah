<x-app-layout>
    <div x-data="{ 
        showBorrowModal: false, 
        borrowItem: { id: '', name: '', stock: 0 } 
    }" class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Card Utama -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Bar Atas: Subtitle -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm font-semibold text-blue-600">
                    Daftar Barang Inventaris
                </div>
            </div>

            <!-- BAGIAN FILTER & LIVE SEARCH -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <!-- Dropdown Tampilkan Data -->
                <form method="GET" action="{{ route('user.items.index') }}" id="perPageForm" class="flex items-center gap-2">
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
                                <th class="px-6 py-3.5">Kode</th>
                                <th class="px-6 py-3.5">Nama</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5">Stok</th>
                                <th class="px-6 py-3.5">Kondisi</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse($items ?? [] as $index => $item)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->code }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $item->category->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-700">
                                        {{ $item->stock }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(strtolower($item->condition) == 'baik')
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-500 text-white uppercase">baik</span>
                                        @elseif(strtolower($item->condition) == 'rusak ringan')
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-amber-500 text-white uppercase">rusak ringan</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-rose-500 text-white uppercase">rusak berat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->stock > 0)
                                            <!-- Tombol Pinjam Berbasis Modal Pop-up -->
                                            <button type="button" 
                                                @click="showBorrowModal = true; borrowItem = { id: '{{ $item->id }}', name: '{{ addslashes($item->name) }}', stock: {{ $item->stock }} }" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm transition text-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Pinjam
                                            </button>
                                        @else
                                            <span class="text-gray-400 font-semibold">Stok Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada data barang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- BAGIAN BAWAH: Render Paginator & Informasi Total Data -->
                <div class="flex flex-col sm:flex-row items-center justify-between pt-2 text-xs text-gray-500 gap-4">
                    <div>
                        @if(isset($items) && $items->total() > 0)
                            Menampilkan {{ $items->firstItem() }} - {{ $items->lastItem() }} dari {{ $items->total() }} data
                        @else
                            Menampilkan 0 data
                        @endif
                    </div>

                    <div>
                        @if(isset($items) && method_exists($items, 'links'))
                            {{ $items->appends(['perPage' => request('perPage'), 'search' => request('search')])->links() }}
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= MODAL POP-UP FORM PEMINJAMAN ================= -->
        <div x-cloak x-show="showBorrowModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showBorrowModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl text-left">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-base font-bold text-gray-800">Form Peminjaman Barang</h3>
                    <button @click="showBorrowModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
                </div>

                <form action="{{ route('loans.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="item_id" x-model="borrowItem.id">

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Barang</label>
                        <input type="text" x-model="borrowItem.name" disabled class="w-full bg-gray-100 border-gray-200 rounded-lg px-3 py-2 text-gray-600 font-medium">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Jumlah Peminjaman (Qty) *</label>
                        <input type="number" name="quantity" min="1" :max="borrowItem.stock" value="1" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <span class="text-[10px] text-gray-400 mt-1 block">Stok tersedia: <span x-text="borrowItem.stock"></span></span>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Tanggal Jatuh Tempo *</label>
                        <input type="date" name="due_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button @click="showBorrowModal = false" type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition">
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Live Search User Barang -->
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