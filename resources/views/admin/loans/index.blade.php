<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peminjaman Barang (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Jml</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tenggat</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($loans as $loan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $loan->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loan->item->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $loan->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $loan->loan_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $loan->due_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $loan->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : ($loan->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : ($loan->status == 'terlambat' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    @if($loan->status == 'pending')
                                        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2 font-bold">Setujui</button>
                                        </form>
                                    @elseif($loan->status == 'dipinjam' || $loan->status == 'terlambat')
                                        <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Proses pengembalian barang ini?');">
                                            @csrf
                                            <select name="condition_on_return" class="text-xs border rounded p-1 mr-1" required>
                                                <option value="baik">Kondisi Baik</option>
                                                <option value="rusak ringan">Rusak Ringan</option>
                                                <option value="rusak berat">Rusak Berat</option>
                                            </select>
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold">Terima Kembali</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $loans->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>