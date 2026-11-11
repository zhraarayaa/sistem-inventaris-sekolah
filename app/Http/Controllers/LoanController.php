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
    // Menampilkan daftar peminjaman dengan filter status tab, search, perPage, & pagination
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $status = $request->input('status', 'all'); // Filter tab status
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman

        // Bersihkan URL jika status bernilai 'all' agar URL lebih rapi dan konsisten
        if ($request->has('status') && $status === 'all') {
            $queryParams = $request->except('status');
            return redirect()->route('loans.index', $queryParams);
        }

        if ($user->role === 'admin') {
            $query = Loan::with(['user', 'item', 'returnLog'])
                ->when($status !== 'all', function ($q) use ($status) {
                    if ($status === 'terlambat') {
                        return $q->where('status', 'dipinjam')->where('due_date', '<', Carbon::today());
                    }
                    return $q->where('status', $status);
                })
                ->when($search, function ($q, $search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%{$search}%");
                    });
                })
                ->latest();

            if ($perPage === 'all') {
                $loans = $query->paginate($query->count() ?: 1)->withQueryString();
            } else {
                $loans = $query->paginate((int) $perPage)->withQueryString();
            }

            return view('admin.loans.index', compact('loans', 'search', 'status', 'perPage'));
        } else {
            $query = Loan::with(['item', 'returnLog'])
                ->where('user_id', $user->id)
                ->when($status !== 'all', function ($q) use ($status) {
                    if ($status === 'terlambat') {
                        return $q->where('status', 'dipinjam')->where('due_date', '<', Carbon::today());
                    }
                    return $q->where('status', $status);
                })
                ->when($search, function ($q, $search) {
                    $q->whereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%{$search}%");
                    });
                })
                ->latest();

            if ($perPage === 'all') {
                $loans = $query->paginate($query->count() ?: 1)->withQueryString();
            } else {
                $loans = $query->paginate((int) $perPage)->withQueryString();
            }

            return view('user.loans.index', compact('loans', 'search', 'status', 'perPage'));
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

    // Admin menyetujui peminjaman
    public function approve(Loan $loan)
    {
        $item = $loan->item;

        if ($loan->quantity > $item->stock) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
        }

        $item->decrement('stock', $loan->quantity);
        $loan->update(['status' => 'dipinjam']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_loan',
            'description' => 'Admin menyetujui peminjaman untuk ' . $loan->user->name . ' (Barang: ' . $item->name . ')',
        ]);

        return redirect()->back()->with('success', 'Peminjaman disetujui dan stok barang diperbarui.');
    }

    // Admin menolak peminjaman dengan alasan dari modal pop-up
    public function reject(Request $request, Loan $loan)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Menyimpan status ditolak beserta alasan ke kolom rejection_reason
        $loan->update([
            'status' => 'ditolak',
            'rejection_reason' => $request->reason,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject_loan',
            'description' => 'Admin menolak peminjaman untuk ' . ($loan->user->name ?? 'User') . '. Alasan: ' . $request->reason,
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }

    // Memproses pengembalian barang
    public function returnItem(Request $request, Loan $loan)
    {
        ReturnLog::create([
            'loan_id' => $loan->id,
            'returned_at' => Carbon::now(),
            'condition_on_return' => $request->input('condition_on_return', 'baik'),
            'notes' => $request->input('notes', 'Dikembalikan oleh user via sistem'),
        ]);

        $loan->update(['status' => 'dikembalikan']);
        
        if ($loan->item) {
            $loan->item->increment('stock', $loan->quantity);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'return_item',
            'description' => 'Pengembalian barang tercatat untuk peminjaman oleh ' . $loan->user->name,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil dikembalikan dan stok diperbarui.');
    }

    // ==========================================
    // TAMBAHAN FITUR NILAI TAMBAH: EXPORT PDF
    // ==========================================
    public function exportPdf(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        $query = Loan::with(['user', 'item', 'returnLog'])
            ->when($status !== 'all', function ($q) use ($status) {
                if ($status === 'terlambat') {
                    return $q->where('status', 'dipinjam')->where('due_date', '<', Carbon::today());
                }
                return $q->where('status', $status);
            })
            ->when($search, function ($q, $search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('item', function ($itemQuery) use ($search) {
                    $itemQuery->where('name', 'like', "%{$search}%");
                });
            })
            ->latest();

        $loans = $query->get();

        return view('admin.loans.pdf', compact('loans', 'status'));
    }
}