<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Controllers\Api\V1\BukuApiController;
use App\Http\Controllers\Api\V1\PeminjamanApiController;
use App\Http\Controllers\Api\V1\ReferensiBukuController;

Route::prefix('v1')->group(function () {
    // 1. ENDPOINT PUBLIK
    Route::post('/auth/login', [AuthTokenController::class, 'login']);
    Route::get('/bukus', [BukuApiController::class, 'index']);
    Route::get('/bukus/{id}', [BukuApiController::class, 'show']);

    // 2. ENDPOINT TERPROTEKSI BEARER TOKEN SANCTUM
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthTokenController::class, 'me']);
        Route::post('/auth/logout', [AuthTokenController::class, 'logout']);

        // Mutasi Katalog Buku
        Route::post('/bukus', [BukuApiController::class, 'store']);
        Route::put('/bukus/{id}', [BukuApiController::class, 'update']);
        Route::patch('/bukus/{id}', [BukuApiController::class, 'update']);
        Route::delete('/bukus/{id}', [BukuApiController::class, 'destroy']);
        Route::get('/bukus/{id}/referensi-eksternal', [ReferensiBukuController::class, 'show']);

        // Transaksi Sirkulasi Peminjaman ACID
        Route::get('/peminjamans', [PeminjamanApiController::class, 'index']);
        Route::post('/peminjamans', [PeminjamanApiController::class, 'store']);
        Route::patch('/peminjamans/{id}/kembalikan', [PeminjamanApiController::class, 'kembalikan']);
    });
});