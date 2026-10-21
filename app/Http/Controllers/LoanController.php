<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Models\ReturnLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    // Menampilkan daftar peminjaman (Admin melihat semua, User melihat miliknya sendiri)
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $loans = Loan::with(['user', 'item', 'returnLog'])->latest()->paginate(10);
            return view('admin.loans.index', compact('loans'));
        } else {
            $loans = Loan::with(['item', 'returnLog'])->where('user_id', $user->id)->latest()->paginate(10);
            return view('user.loans.index', compact('loans'));
        }
    }

    // Form pengajuan peminjaman (untuk User)
    public function create()
    {
        $items = Item::where('stock', '>', 0)->get();
        return view('user.loans.create', compact('items'));
    }

    // Menyimpan pengajuan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->quantity > $item->stock) {
            return back()->withErrors(['quantity' => 'Jumlah peminjaman melebihi stok yang tersedia.'])->withInput();
        }

        Loan::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'loan_date' => Carbon::now(),
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_loan',
            'description' => auth()->user()->name . ' mengajukan peminjaman barang: ' . $item->name,
        ]);

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    // Admin menyetujui peminjaman (mengubah status jadi 'dipinjam' dan kurangi stok)
    public function approve(Loan $loan)
    {
        $item = $loan->item;

        if ($loan->quantity > $item->stock) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
        }

        // Kurangi stok barang
        $item->decrement('stock', $loan->quantity);

        $loan->update(['status' => 'dipinjam']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_loan',
            'description' => 'Admin menyetujui peminjaman untuk ' . $loan->user->name . ' (Barang: ' . $item->name . ')',
        ]);

        return redirect()->back()->with('success', 'Peminjaman disetujui dan stok barang diperbarui.');
    }

    // Memproses pengembalian barang
    public function returnItem(Request $request, Loan $loan)
    {
        $request->validate([
            'condition_on_return' => 'required|in:baik,rusak ringan,rusak berat',
            'notes' => 'nullable|string',
        ]);

        // Catat log pengembalian
        ReturnLog::create([
            'loan_id' => $loan->id,
            'returned_at' => Carbon::now(),
            'condition_on_return' => $request->condition_on_return,
            'notes' => $request->notes,
        ]);

        // Update status peminjaman menjadi dikembalikan
        $loan->update(['status' => 'dikembalikan']);

        // Kembalikan stok barang
        $loan->item->increment('stock', $loan->quantity);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_item',
            'description' => 'Pengembalian barang tercatat untuk peminjaman oleh ' . $loan->user->name,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil dikembalikan dan stok diperbarui.');
    }
}