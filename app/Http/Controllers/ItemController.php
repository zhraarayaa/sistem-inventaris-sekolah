<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan daftar barang khusus untuk User/Siswa
    public function userIndex(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman

        $query = Item::with('category')
            ->when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            })
            ->latest();

        if ($perPage === 'all') {
            $items = $query->paginate($query->count() ?: 1)->withQueryString();
        } else {
            $items = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('user.items.index', compact('items', 'search', 'perPage'));
    }

    // Menampilkan daftar barang dengan search, perPage filter, & pagination (Admin)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman

        $query = Item::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest();

        // Logika menangani pilihan filter perPage (termasuk opsi 'all')
        if ($perPage === 'all') {
            $items = $query->paginate($query->count() ?: 1)->withQueryString();
        } else {
            $items = $query->paginate((int) $perPage)->withQueryString();
        }

        // Ambil semua data kategori agar dropdown pada modal tambah/edit barang muncul
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'search', 'perPage', 'categories'));
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

        $item = Item::create($request->all());

        // Catat aktivitas tambah barang ke Activity Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_item',
            'description' => 'Menambahkan barang ' . $item->name,
        ]);

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

        // Catat aktivitas update barang ke Activity Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_item',
            'description' => 'Memperbarui data barang ' . $item->name,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    // Menghapus barang (Soft Delete)
    public function destroy(Item $item)
    {
        $itemName = $item->name;
        $item->delete();

        // Catat aktivitas hapus barang ke Activity Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_item',
            'description' => 'Menghapus barang ' . $itemName,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil dihapus.');
    }
}