<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;

// Redirect root ke /buku
Route::get('/', function () {
    return redirect()->route('buku.index');
});

// Guest Routes (Khusus Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (Logout)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Public Routes (Bisa diakses publik)
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Resource Routes Buku & Kategori
Route::resource('buku', BukuController::class)->except(['index']);
Route::resource('kategori', KategoriController::class);

// Transaksi Sirkulasi
Route::post('/buku/{buku}/pinjam', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::post('/peminjaman/{id}/kembali', [PeminjamanController::class, 'returnBook'])->name('peminjaman.return');