<?php

use App\Http\Controllers\ActivityLogController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (root) langsung ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Route khusus Admin (Manajemen Kategori, Barang, User, Approval, Reject & Pengembalian)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class);
        Route::resource('users', UserController::class);
        
        // Admin Approval, Reject & Return Actions
        Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject'); // <-- Dipindah ke sini agar prefix 'admin.' aktif
        Route::post('/loans/{loan}/return', [LoanController::class, 'returnItem'])->name('loans.return');

        // Route Activity Log untuk Admin
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Route Return Logs / Pengembalian untuk Admin
        Route::get('/return-logs', [ReturnLogController::class, 'index'])->name('return-logs.index');

        // Rute Export PDF Laporan Peminjaman
        Route::get('/loans/export-pdf', [LoanController::class, 'exportPdf'])->name('loans.export-pdf');


    });

    // Route Peminjaman Umum (Bisa diakses Admin & User dengan kontrol di Controller)
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

    // Route Peminjaman Khusus User (Tambahan untuk tombol tab & aksi user)
    Route::get('/user/loans', [LoanController::class, 'index'])->name('user.loans.index');
    Route::patch('/user/loans/{loan}/return', [LoanController::class, 'returnItem'])->name('user.loans.return');

    // Route Halaman Data Barang Khusus User/Siswa
    Route::get('/user/items', [ItemController::class, 'userIndex'])->name('user.items.index');

    // Dashboard utama setelah login (Dipisah otomatis antara Admin dan User)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            $totalItems = \App\Models\Item::count();
            $totalStock = \App\Models\Item::sum('stock');
            $activeLoans = \App\Models\Loan::where('status', 'dipinjam')->count();
            $pendingLoans = \App\Models\Loan::where('status', 'pending')->count();
            
            $latestItems = \App\Models\Item::latest()->take(5)->get();
            $latestLoans = \App\Models\Loan::with(['user', 'item'])->latest()->take(5)->get();

            // Tambahan data untuk Grafik Statistik Admin (Chart.js)
            $loanChartData = [];
            for ($month = 1; $month <= 12; $month++) {
                $loanChartData[] = \App\Models\Loan::whereMonth('created_at', $month)
                    ->whereYear('created_at', date('Y'))
                    ->count();
            }

            // Tambahan data untuk Top 5 Barang Favorit
            $topItems = \App\Models\Item::withCount('loans')
                ->orderBy('loans_count', 'desc')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'totalItems', 'totalStock', 'activeLoans', 'pendingLoans', 
                'latestItems', 'latestLoans', 'loanChartData', 'topItems'
            ));
        }

        // Data statistik khusus User / Siswa
        $userId = auth()->id();
        $totalItems = \App\Models\Item::count();
        $totalStock = \App\Models\Item::sum('stock');
        $activeLoans = \App\Models\Loan::where('user_id', $userId)->where('status', 'dipinjam')->count();
        $lateLoans = \App\Models\Loan::where('user_id', $userId)
                    ->where('status', 'dipinjam')
                    ->where('due_date', '<', now())
                    ->count();
        $recentLoans = \App\Models\Loan::with('item')
                    ->where('user_id', $userId)
                    ->latest()
                    ->take(5)
                    ->get();

        return view('dashboard', compact('totalItems', 'totalStock', 'activeLoans', 'lateLoans', 'recentLoans'));
    })->name('dashboard');

    // Route Profile bawaan Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';