<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Menampilkan riwayat aktivitas dengan search, perPage, & pagination
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman

        $query = ActivityLog::with('user')
            ->when($search, function ($q, $search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('created_at', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            })
            ->latest();

        if ($perPage === 'all') {
            $activityLogs = $query->paginate($query->count() ?: 1)->withQueryString();
        } else {
            $activityLogs = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.activity-logs.index', compact('activityLogs', 'search', 'perPage'));
    }
}