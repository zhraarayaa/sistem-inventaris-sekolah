<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan daftar barang dengan search & pagination
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $items = Item::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.items.index', compact('items', 'search'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    // Menyimpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
        ]);

        Item::create($request->all());

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    // Menampilkan form edit barang
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    // Memperbarui data barang
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
        ]);

        $item->update($request->all());

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    // Menghapus barang (Soft Delete)
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil dihapus.');
    }
}