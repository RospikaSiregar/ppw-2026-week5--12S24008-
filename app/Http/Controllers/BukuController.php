<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class BukuController extends Controller
{
    // 1. Tampilkan Semua Buku (Eager Loading + Pagination)
    public function index(Request $request)
{
    // Simpan histori pencarian terakhir ke session
    if ($request->filled('search')) {
        session(['last_buku_search' => $request->search]);
    }

    // Katalog Buku dengan Eager Loading, withCount, & Dynamic Search (when)
    $bukus = Buku::with('kategori')
        ->withCount('peminjamans')
        ->when($request->search, function ($query, $search) {
            return $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%");
        })
        ->when($request->kategori_id, function ($query, $kategoriId) {
            return $query->where('kategori_id', $kategoriId);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $kategoris = Kategori::all();

    // Kartu Statistik Perpustakaan menggunakan Fluent Query Builder (DB::table)
    $stats = [
        'total_judul' => DB::table('bukus')->count(),
        'total_stok' => DB::table('bukus')->sum('stok'),
        'total_dipinjam' => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
        'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
    ];

    return view('buku.index', compact('bukus', 'kategoris', 'stats'));
}

    // 2. Form Tambah Buku
    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    // 3. Simpan Buku Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|unique:bukus,isbn',
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // 4. Detail Buku
    public function show(Buku $buku)
    {
        return view('buku.show', compact('buku'));
    }

    // 5. Form Edit Buku
    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    // 6. Update Data Buku
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|unique:bukus,isbn,' . $buku->id,
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    // 7. Hapus Buku
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}