<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;

// Public View
Route::get('/', function () { return redirect()->route('buku.index'); });
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');

// Guest Routes (Khusus Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (Wajib Login Pustakawan)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CRUD Mutasi Data
    Route::resource('buku', BukuController::class)->except(['index', 'show']);
    Route::resource('kategori', KategoriController::class);

    // Transaksi Sirkulasi
    Route::post('/buku/{buku}/pinjam', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::post('/peminjaman/{id}/kembali', [PeminjamanController::class, 'returnBook'])->name('peminjaman.return');
});