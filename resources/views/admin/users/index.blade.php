<x-app-layout>
    <div x-data="{ 
        showCreateModal: false,
        showDeleteModal: false, 
        deleteUrl: '', 
        userName: '',
        showEditModal: false,
        editUser: { id: '', name: '', email: '', role: '' }
    }" class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Message untuk Notifikasi Sukses/Error -->
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

        <!-- Card Utama -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 space-y-6">
            
            <!-- Bar Atas: Subtitle & Tombol Tambah -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm font-semibold text-blue-600">
                    Daftar User
                </div>

                <button @click="showCreateModal = true" type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition text-xs flex items-center gap-2">
                    + Tambah
                </button>
            </div>

            <!-- BAGIAN FILTER & LIVE SEARCH -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <form method="GET" action="{{ route('admin.users.index') }}" id="perPageForm" class="flex items-center gap-2">
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

                <div class="w-full sm:w-auto flex items-center gap-2">
                    <span>Cari:</span>
                    <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full sm:w-64 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50/50">
                </div>
            </div>

            <!-- WRAPPER KONTEN TABEL & PAGINATION -->
            <div id="tableContentWrapper" class="space-y-6">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50/70 text-gray-600 uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">#</th>
                                <th class="px-6 py-3.5">Nama</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5">Role</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse($users ?? [] as $index => $user)
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $users->firstItem() ? $users->firstItem() + $index : $index + 1 }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @if(strtolower($user->role) == 'admin')
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-rose-600 text-white uppercase">Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-gray-600 text-white uppercase">User</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-1">
                                        <!-- Tombol Pop-up Edit -->
                                        <button @click="showEditModal = true; editUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}' }" class="inline-flex items-center justify-center p-1.5 bg-amber-400 hover:bg-amber-500 text-white rounded shadow transition" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Tombol Pop-up Hapus -->
                                        @if(auth()->id() !== $user->id)
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('admin.users.destroy', $user->id) }}'; userName = '{{ $user->name }}'" class="inline-flex items-center justify-center p-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded shadow transition" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Informasi & Pagination -->
                <div class="flex flex-col sm:flex-row items-center justify-between pt-2 text-xs text-gray-500 gap-4">
                    <div>
                        @if(isset($users) && $users->total() > 0)
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
                        @else
                            Menampilkan 0 data
                        @endif
                    </div>
                    <div>
                        @if(isset($users) && method_exists($users, 'links'))
                            {{ $users->appends(['perPage' => request('perPage'), 'search' => request('search')])->links() }}
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= MODAL TAMBAH USER ================= -->
        <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
            <div @click.away="showCreateModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl text-left">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-base font-bold text-gray-800">Tambah User Baru</h3>
                    <button @click="showCreateModal = false" type="button" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
                </div>

                <!-- Notifikasi Error Validasi di dalam Modal -->
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-3 rounded-lg text-rose-700 text-xs">
                        <p class="font-bold">Gagal Menyimpan Data:</p>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" autofocus placeholder="Nama lengkap" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@sekolah.test" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Password (Minimal 8 Karakter) <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Password minimal 8 karakter" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Role / Hak Akses <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Siswa/Guru)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button @click="showCreateModal = false" type="button" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL HAPUS USER -->
        <div x-cloak x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
            <div @click.away="showDeleteModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 text-center space-y-4 shadow-xl">
                <div class="mx-auto w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-500 text-2xl font-bold">!</div>
                <h3 class="text-lg font-bold text-gray-800">Hapus User?</h3>
                <p class="text-xs text-gray-600">User "<span class="font-semibold text-gray-800" x-text="userName"></span>" akan dihapus beserta data terkait.</p>
                <div class="flex justify-center gap-3 pt-2">
                    <button @click="showDeleteModal = false" type="button" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-xs font-bold rounded-xl transition">Batal</button>
                    <form :action="deleteUrl" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT USER -->
        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl text-left">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-base font-bold text-gray-800">Edit User</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
                </div>
                <form :action="'/admin/users/' + editUser.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama *</label>
                        <input type="text" name="name" x-model="editUser.name" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" x-model="editUser.email" required class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Role *</label>
                        <select name="role" x-model="editUser.role" class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border-gray-200 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button @click="showEditModal = false" type="button" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Live Search User -->
    <script>
        document.getElementById('liveSearchInput').addEventListener('input', function() {
            let searchValue = this.value;
            let perPageSelect = document.querySelector('select[name="perPage"]');
            let perPage = perPageSelect ? perPageSelect.value : 10;
            
            let url = new URL(window.location.href);
            url.searchParams.set('search', searchValue);
            url.searchParams.set('perPage', perPage);
            url.searchParams.set('page', 1);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let newContent = doc.getElementById('tableContentWrapper');
                if (newContent) {
                    document.getElementById('tableContentWrapper').innerHTML = newContent.innerHTML;
                }
                window.history.pushState({}, '', url);
            });
        });
    </script>
</x-app-layout>