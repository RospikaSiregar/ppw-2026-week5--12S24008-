<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeminjamanApiController extends Controller
{
    use ApiResponse;

    // POST /api/v1/peminjamans (ACID Transaction + Pessimistic Locking)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|max:20',
            'nama_peminjam' => 'required|string|max:255',
            'durasi_hari' => 'required|integer|min:1|max:30',
        ]);

        return DB::transaction(function () use ($validated) {
            // Lock record buku untuk mencegah race condition (Pessimistic Locking)
            $buku = Buku::where('id', $validated['buku_id'])
                ->lockForUpdate()
                ->first();

            // Cek ketersediaan stok
            if ($buku->stok <= 0) {
                return $this->problemResponse(
                    title: 'Stock Out',
                    detail: "Stok buku '{$buku->judul}' sedang habis dan tidak dapat dipinjam.",
                    status: 409,
                    errorSlug: 'book-out-of-stock'
                );
            }

            // Cek apakah peminjam masih memiliki peminjaman aktif untuk buku yang sama
            $peminjamanAktif = Peminjaman::where('nim_peminjam', $validated['nim_peminjam'])
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($peminjamanAktif) {
                return $this->problemResponse(
                    title: 'Duplicate Loan Active',
                    detail: "Mahasiswa dengan NIM {$validated['nim_peminjam']} masih meminjam buku ini.",
                    status: 409,
                    errorSlug: 'duplicate-active-loan'
                );
            }

            // Dekremen stok buku secara atomik
            $buku->decrement('stok');

            // Generasi Kode Pinjam Unik (misal: PJM-20261110-XXXX)
            $kodePinjam = 'PJM-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $tanggalPinjam = Carbon::now();
            $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays($validated['durasi_hari']);

            // Simpan Transaksi Peminjaman
            $peminjaman = Peminjaman::create([
                'kode_pinjam' => $kodePinjam,
                'buku_id' => $buku->id,
                'nim_peminjam' => $validated['nim_peminjam'],
                'nama_peminjam' => $validated['nama_peminjam'],
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'status' => 'dipinjam',
            ]);

            return $this->createdResponse(
                data: [
                    'id' => $peminjaman->id,
                    'kode_pinjam' => $peminjaman->kode_pinjam,
                    'buku' => [
                        'id' => $buku->id,
                        'judul' => $buku->judul,
                        'sisa_stok' => $buku->stok,
                    ],
                    'peminjam' => [
                        'nim' => $peminjaman->nim_peminjam,
                        'nama' => $peminjaman->nama_peminjam,
                    ],
                    'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => $peminjaman->tanggal_jatuh_tempo,
                    'status' => $peminjaman->status,
                ],
                locationUrl: url("/api/v1/peminjamans/{$peminjaman->id}"),
                message: 'Transaksi peminjaman berhasil diproses.'
            );
        });
    }
}