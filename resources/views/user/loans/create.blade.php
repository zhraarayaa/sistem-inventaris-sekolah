<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pengajuan Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="item_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Barang <span class="text-red-500">*</span></label>
                        <select name="item_id" id="item_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('item_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Barang Tersedia --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (Stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pinjam <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('quantity') border-red-500 @enderror" required>
                        @error('quantity')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Tenggat Pengembalian <span class="text-red-500">*</span></label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('due_date') border-red-500 @enderror" required>
                        @error('due_date')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Ajukan Peminjaman
                        </button>
                        <a href="{{ route('loans.index') }}" class="text-gray-600 hover:underline">Kembali</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>