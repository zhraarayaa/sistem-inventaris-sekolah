<?php

namespace App\Http\Controllers;

use App\Models\ReturnLog;
use Illuminate\Http\Request;

class ReturnLogController extends Controller
{
    // Menampilkan riwayat pengembalian dengan search, perPage, & pagination
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman

        $query = ReturnLog::with(['loan.user', 'loan.item'])
            ->when($search, function ($q, $search) {
                $q->whereHas('loan.user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('loan.item', function ($itemQuery) use ($search) {
                    $itemQuery->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            })
            ->latest('returned_at');

        if ($perPage === 'all') {
            $returnLogs = $query->paginate($query->count() ?: 1)->withQueryString();
        } else {
            $returnLogs = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.return-logs.index', compact('returnLogs', 'search', 'perPage'));
    }
}