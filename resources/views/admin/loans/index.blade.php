<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ 
        isRejectOpen: false, 
        rejectActionUrl: '', 
        openRejectModal(url) { 
            this.rejectActionUrl = url; 
            this.isRejectOpen = true; 
        } 
    }">
        
        <!-- Flash Message untuk Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-700 text-xs font-semibold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl text-rose-700 text-xs font-semibold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Utama / Tabel Peminjaman -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Bar Atas Tabel: Filter Tab & Tombol Export PDF -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                
                <!-- Tab Filter Status -->
                <div class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-xl border border-gray-100 text-xs font-semibold overflow-x-auto">
                    <a href="{{ route('loans.index', array_filter(['search' => request('search'), 'perPage' => request('perPage')])) }}" class="px-3.5 py-1.5 rounded-lg transition {{ !request('status') || request('status') == 'all' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-200/50' }}">
                        all
                    </a>
                    <a href="{{ route('loans.index', array_filter(['status' => 'pending', 'search' => request('search'), 'perPage' => request('perPage')])) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('status') == 'pending' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-200/50' }}">
                        pending
                    </a>
                    <a href="{{ route('loans.index', array_filter(['status' => 'dipinjam', 'search' => request('search'), 'perPage' => request('perPage')])) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('status') == 'dipinjam' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-200/50' }}">
                        dipinjam
                    </a>
                    <a href="{{ route('loans.index', array_filter(['status' => 'dikembalikan', 'search' => request('search'), 'perPage' => request('perPage')])) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('status') == 'dikembalikan' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-200/50' }}">
                        dikembalikan
                    </a>
                    <a href="{{ route('loans.index', array_filter(['status' => 'terlambat', 'search' => request('search'), 'perPage' => request('perPage')])) }}" class="px-3.5 py-1.5 rounded-lg transition {{ request('status') == 'terlambat' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-200/50' }}">
                        terlambat
                    </a>
                </div>

                <!-- Tombol Export PDF Laporan (Nilai Tambah) -->
                <div>
                    <a href="{{ route('admin.loans.export-pdf', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow transition gap-1.5">
                        <span> Export PDF</span>
                    </a>
                </div>

            </div>

            <!-- Kontrol Tabel: Tampilkan Data & Pencarian -->
            <form method="GET" action="{{ route('loans.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs text-gray-600">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                
                <div class="flex items-center space-x-2">
                    <span>Tampilkan</span>
                    <select name="perPage" onchange="this.form.submit()" class="border-gray-200 rounded-lg text-xs focus:border-blue-500 focus:ring-blue-500 py-1 px-2">
                        <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ !request('perPage') || request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <span>data</span>
                </div>

                <div class="flex items-center space-x-2">
                    <span>Cari:</span>
                    <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="border-gray-200 rounded-lg text-xs focus:border-blue-500 focus:ring-blue-500 py-1 px-3 w-48 sm:w-64" placeholder="Cari peminjaman...">
                </div>
            </form>

            <!-- Tabel Data Peminjaman -->
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-gray-50/70 text-gray-600 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">#</th>
                            <th class="px-6 py-3.5">Peminjam</th>
                            <th class="px-6 py-3.5">Barang</th>
                            <th class="px-6 py-3.5">Qty</th>
                            <th class="px-6 py-3.5">Tgl Pinjam</th>
                            <th class="px-6 py-3.5">Jatuh Tempo</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                        @forelse($loans ?? [] as $index => $loan)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-6 py-4 font-mono font-medium text-gray-500">
                                    #{{ $loans->firstItem() ? $loans->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $loan->user->name ?? '-' }} @if($loan->user_id === auth()->id()) <span class="text-gray-400 font-normal">(saya)</span> @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    {{ $loan->item->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800">
                                    {{ $loan->quantity }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ \Carbon\Carbon::parse($loan->borrow_date ?? $loan->loan_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($loan->status == 'dikembalikan')
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-500 text-white uppercase tracking-wider">dikembalikan</span>
                                    @elseif($loan->status == 'dipinjam')
                                        @if(\Carbon\Carbon::parse($loan->due_date)->isPast())
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-rose-500 text-white uppercase tracking-wider">terlambat</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-amber-500 text-white uppercase tracking-wider">dipinjam</span>
                                        @endif
                                    @elseif($loan->status == 'ditolak')
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-rose-600 text-white uppercase tracking-wider">ditolak</span>
                                        @if($loan->rejection_reason)
                                            <p class="text-[11px] text-rose-600 mt-1 italic font-medium">Alasan: "{{ $loan->rejection_reason }}"</p>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-gray-500 text-white uppercase tracking-wider">pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    @if(auth()->user()->role === 'admin' && $loan->status === 'pending')
                                        <!-- Tombol Approve & Tolak Admin -->
                                        <div class="inline-flex items-center space-x-1">
                                            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow transition">
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <!-- Tombol Tolak memicu Modal Alpine.js -->
                                            <button type="button" @click="openRejectModal('{{ route('admin.loans.reject', $loan->id) }}')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow transition">
                                                Tolak
                                            </button>
                                        </div>
                                    @elseif($loan->status === 'dipinjam' && auth()->user()->role !== 'admin' && $loan->user_id === auth()->id())
                                        <!-- Tombol Kembalikan khusus User/Siswa dengan SweetAlert2 -->
                                        <button type="button" onclick="confirmReturn('{{ route('user.loans.return', $loan->id) }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow transition">
                                            Kembalikan
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    Belum ada data peminjaman barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Tabel: Info Keterangan Data & Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                <!-- Teks Info Menampilkan Data -->
                <div class="text-xs text-gray-500">
                    @if(isset($loans) && $loans->total() > 0)
                        Menampilkan {{ $loans->firstItem() }} - {{ $loans->lastItem() }} dari {{ $loans->total() }} data
                    @else
                        Menampilkan 0 data
                    @endif
                </div>

                <!-- Custom Pagination Links -->
                @if(isset($loans) && method_exists($loans, 'links'))
                    <div>
                        {{ $loans->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Modal Pop-up Alasan Penolakan -->
        <div x-show="isRejectOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             style="display: none;"
             x-cloak>
            
            <div @click.outside="isRejectOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800">Alasan Penolakan Peminjaman</h3>
                    <button @click="isRejectOpen = false" class="text-gray-400 hover:text-gray-600 text-sm font-bold">&times;</button>
                </div>

                <form :action="rejectActionUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Berikan alasan penolakan *</label>
                        <textarea name="reason" rows="3" required placeholder="Contoh: Stok barang sedang habis atau jadwal tidak sesuai." class="w-full rounded-xl border-gray-200 text-xs focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 p-3"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="isRejectOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script SweetAlert2 untuk Pop-up Konfirmasi Tengah -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmReturn(actionUrl) {
            Swal.fire({
                title: 'Kembalikan Barang?',
                text: "Pastikan kondisi barang sudah dicek sebelum dikembalikan ke inventaris.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = actionUrl;
                    form.method = 'POST';
                    
                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PATCH';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>
</x-app-layout>