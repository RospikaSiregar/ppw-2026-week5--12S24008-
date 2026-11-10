<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PeminjamanResource;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanApiController extends Controller
{
    use ApiResponse;

    // GET /api/v1/peminjamans (Bearer Token + Filter status/nim + Pagination)
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $paginator = Peminjaman::with('buku')
            ->when($request->query('status'), fn ($q, $st) => $q->where('status', $st))
            ->when($request->query('nim'), fn ($q, $nim) => $q->where('nim_peminjam', $nim))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return $this->successResponse(
            data: PeminjamanResource::collection($paginator->items()),
            message: 'Daftar sirkulasi peminjaman buku berhasil diambil.',
            meta: [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total_items' => $paginator->total(),
                'total_pages' => $paginator->lastPage(),
            ]
        );
    }

    // POST /api/v1/peminjamans (Bearer Token + Transaksi ACID & lockForUpdate)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|max:15',
            'nama_peminjam' => 'required|string|max:150',
            'durasi_hari' => 'nullable|integer|min:1|max:30',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Kunci baris buku secara eksklusif (SELECT ... FOR UPDATE) cegah Race Condition
            $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->first();

            if (!$buku) {
                return $this->problemResponse('Resource Not Found', 'Buku tidak ditemukan.', 404, 'buku-not-found');
            }

            // 2. Cek apakah mahasiswa masih meminjam buku yang sama dan belum dikembalikan (409 Conflict)
            $alreadyBorrowed = Peminjaman::where('buku_id', $buku->id)
                ->where('nim_peminjam', strtoupper(trim($validated['nim_peminjam'])))
                ->where('status', 'dipinjam')
                ->exists();

            if ($alreadyBorrowed) {
                return $this->problemResponse(
                    title: 'Duplicate Active Loan Conflict',
                    detail: "Mahasiswa dengan NIM '{$validated['nim_peminjam']}' masih meminjam buku '{$buku->judul}' dan belum mengembalikannya.",
                    status: 409,
                    errorSlug: 'duplicate-active-loan'
                );
            }

            // 3. Cek stok ketersediaan buku (422 Unprocessable Content jika stok habis)
            if ($buku->stok < 1) {
                return $this->problemResponse(
                    title: 'Book Stock Exhausted',
                    detail: "Maaf, stok buku '{$buku->judul}' sedang habis (0 tersisa).",
                    status: 422,
                    errorSlug: 'stock-exhausted'
                );
            }

            // 4. Kurangi stok buku (-1) dan simpan data peminjaman baru secara atomik
            $buku->decrement('stok');

            $durasi = $validated['durasi_hari'] ?? 7;
            $peminjaman = Peminjaman::create([
                'buku_id' => $buku->id,
                'nim_peminjam' => strtoupper(trim($validated['nim_peminjam'])),
                'nama_peminjam' => $validated['nama_peminjam'],
                'tanggal_pinjam' => now()->toDateString(),
                'status' => 'dipinjam',
            ]);

            $peminjaman->load('buku');

            return $this->createdResponse(
                data: new PeminjamanResource($peminjaman),
                locationUrl: url("/api/v1/peminjamans?nim={$peminjaman->nim_peminjam}"),
                message: "Berhasil meminjam buku '{$buku->judul}'. Sisa stok kini: {$buku->fresh()->stok}."
            );
        });
    }

    // PATCH /api/v1/peminjamans/{id}/kembalikan (Bearer Token + Transaksi ACID & lockForUpdate)
    public function kembalikan(string $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::find($id);

            if (!$peminjaman) {
                return $this->problemResponse('Resource Not Found', 'Data peminjaman tidak ditemukan.', 404, 'loan-not-found');
            }

            // Tolak jika buku sudah berstatus 'dikembalikan' (409 Conflict)
            if ($peminjaman->status === 'dikembalikan') {
                return $this->problemResponse(
                    title: 'Conflict Already Returned',
                    detail: 'Buku ini sudah dikembalikan sebelumnya.',
                    status: 409,
                    errorSlug: 'already-returned'
                );
            }

            // Lock baris buku
            $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->first();

            // Ubah status peminjaman & tambah stok buku (+1)
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            if ($buku) {
                $buku->increment('stok');
            }

            $peminjaman->load('buku');

            return $this->successResponse(
                data: new PeminjamanResource($peminjaman),
                message: "Buku '{$buku?->judul}' berhasil dikembalikan. Stok buku bertambah menjadi: {$buku?->fresh()->stok}."
            );
        });
    }
}