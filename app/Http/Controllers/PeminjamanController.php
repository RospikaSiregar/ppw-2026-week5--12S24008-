<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use RuntimeException;

class PeminjamanController extends Controller
{
    // Transaksi Peminjaman Buku (ACID + LockForUpdate)
    public function store(Request $request, $buku_id)
    {
        $validated = $request->validate([
            'nim_peminjam' => 'required|string|min:6|max:15',
            'nama_peminjam' => 'required|string|min:3|max:150',
        ]);

        try {
            DB::transaction(function () use ($validated, $buku_id) {
                // Pessimistic Locking untuk mencegah race condition stok
                $buku = Buku::where('id', $buku_id)->lockForUpdate()->firstOrFail();

                if ($buku->stok < 1) {
                    throw new RuntimeException("Stok buku '{$buku->judul}' sedang kosong!");
                }

                // Cek apakah NIM ini masih meminjam buku yang sama dan belum dikembalikan
                $existingPinjam = Peminjaman::where('buku_id', $buku_id)
                    ->where('nim_peminjam', strtoupper(trim($validated['nim_peminjam'])))
                    ->where('status', 'dipinjam')
                    ->first();

                if ($existingPinjam) {
                    throw new RuntimeException("Mahasiswa dengan NIM ini masih meminjam buku yang sama!");
                }

                // Kurangi Stok Buku secara Atomik
                $buku->decrement('stok');

                // Catat Transaksi Peminjaman
                Peminjaman::create([
                    'kode_pinjam' => 'PINJAM-' . strtoupper(Str::random(6)),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => strtoupper(trim($validated['nim_peminjam'])),
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                    'status' => 'dipinjam',
                ]);
            }, 3);

            return back()->with('success', 'Buku berhasil dipinjam secara atomik!');
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error("[DB EXCEPTION PEMINJAMAN]: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan basis data saat memproses peminjaman.');
        }
    }

    // Transaksi Pengembalian Buku (ACID + LockForUpdate)
    public function returnBook($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $peminjaman = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($peminjaman->status === 'dikembalikan') {
                    throw new RuntimeException("Buku ini sudah dikembalikan sebelumnya.");
                }

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // Tambahkan kembali Stok Buku secara Atomik
                $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->firstOrFail();
                $buku->increment('stok');
            }, 3);

            return back()->with('success', 'Buku berhasil dikembalikan dan stok telah bertambah!');
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error("[DB EXCEPTION PENGEMBALIAN]: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan basis data saat memproses pengembalian.');
        }
    }
}