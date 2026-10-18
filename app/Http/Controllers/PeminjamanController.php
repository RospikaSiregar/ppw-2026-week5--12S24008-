<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function store(Request $request, $bukuId)
    {
        return DB::transaction(function () use ($bukuId) {
            // Lock row untuk transaksi ACID sesuai rubrik penilaian
            $buku = Buku::where('id', $bukuId)->lockForUpdate()->firstOrFail();

            if ($buku->stok <= 0) {
                return back()->with('error', 'Stok buku habis!');
            }

            // Kurangi stok buku 1 secara atomik
            $buku->decrement('stok');

            // Generate Kode PJM unik (contoh: PJM-20261018-XXXX)
            $kodePinjam = 'PJM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // Ambil data user login atau fallback data mahasiswa Del
            $user = Auth::user();
            $nim  = $user->nim ?? '12S24008';
            $nama = $user->name ?? 'Rospika Sarah';

            // Catat transaksi ke tabel peminjamans lengkap sesuai skema migrasi
            Peminjaman::create([
                'kode_pinjam'          => $kodePinjam,
                'buku_id'              => $buku->id,
                'user_id'              => $user->id ?? 1,
                'nim_peminjam'         => $nim,
                'nama_peminjam'        => $nama,
                'tanggal_pinjam'       => now()->toDateString(),
                'tanggal_jatuh_tempo'  => now()->addDays(7)->toDateString(), // Batas pengembalian 7 hari
                'status'               => 'dipinjam',
            ]);

            return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam dengan kode ' . $kodePinjam . '!');
        });
    }
}