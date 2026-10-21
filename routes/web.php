<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Mengارahkan halaman utama (root) langsung ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Route khusus Admin (Manajemen Kategori, Barang, User, Approval & Pengembalian)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class);
        Route::resource('users', UserController::class);
        
        // Admin Approval & Return Actions
        Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/return', [LoanController::class, 'returnItem'])->name('loans.return');
    });

    // Route Peminjaman (Bisa diakses Admin & User dengan kontrol di Controller)
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

    // Route Halaman Data Barang Khusus User/Siswa (Baru Ditambahkan)
    Route::get('/user/items', function () {
        return view('user.items.index');
    })->name('user.items.index');

    // Dashboard utama setelah login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route Profile bawaan Laravel Breeze (Menyelesaikan error navigation)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';