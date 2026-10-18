<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Halaman Riwayat Peminjaman & Pengembalian
    public function index()
    {
        $peminjamans = Peminjaman::with('buku')->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjamans'));
    }

    // Transaksi Pinjam Buku (ACID & Lock)
    public function store(Request $request, $bukuId)
    {
        return DB::transaction(function () use ($bukuId) {
            $buku = Buku::where('id', $bukuId)->lockForUpdate()->firstOrFail();

            if ($buku->stok <= 0) {
                return back()->with('error', 'Stok buku habis!');
            }

            $buku->decrement('stok');

            $kodePinjam = 'PJM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $user = Auth::user();

            Peminjaman::create([
                'kode_pinjam'         => $kodePinjam,
                'buku_id'             => $buku->id,
                'user_id'             => $user->id ?? 1,
                'nim_peminjam'        => $user->nim ?? '12S24008',
                'nama_peminjam'       => $user->name ?? 'Rospika Sarah',
                'tanggal_pinjam'      => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                'status'              => 'dipinjam',
            ]);

            return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam!');
        });
    }

    // Transaksi Kembali Buku (ACID & Lock)
    public function kembali($id)
    {
        return DB::transaction(function () use ($id) {
            // Lock row peminjaman
            $peminjaman = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($peminjaman->status === 'dikembalikan') {
                return back()->with('error', 'Buku ini sudah dikembalikan!');
            }

            // Update status & tanggal kembali
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            // Lock row buku & tambah stok kembali (+1)
            $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->firstOrFail();
            $buku->increment('stok');

            return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dikembalikan!');
        });
    }
}