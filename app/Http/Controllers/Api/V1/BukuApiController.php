<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BukuResource;
use App\Models\Buku;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuApiController extends Controller
{
    use ApiResponse;

    // GET /api/v1/bukus
    public function index(Request $request): JsonResponse
    {
        $query = Buku::query();

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->query('kategori_id'));
        }

        $perPage = (int) $request->query('per_page', 10);
        $bukus = $query->paginate($perPage);

        return $this->successResponse(
            data: BukuResource::collection($bukus->items()),
            message: 'Katalog buku berhasil diambil.',
            meta: [
                'current_page' => $bukus->currentPage(),
                'per_page' => $bukus->perPage(),
                'total' => $bukus->total(),
                'last_page' => $bukus->lastPage(),
            ]
        );
    }

    // GET /api/v1/bukus/{id}
    public function show(int $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Buku Not Found',
                detail: "Buku dengan ID {$id} tidak ditemukan dalam sistem.",
                status: 404,
                errorSlug: 'buku-not-found'
            );
        }

        return $this->successResponse(
            data: new BukuResource($buku),
            message: 'Detail data buku berhasil diambil.'
        );
    }

    // POST /api/v1/bukus
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'isbn' => 'required|string|unique:bukus,isbn',
            'stok' => 'required|integer|min:0',
        ]);

        $buku = Buku::create($validated);
        $locationUrl = url("/api/v1/bukus/{$buku->id}");

        return $this->createdResponse(
            data: new BukuResource($buku),
            locationUrl: $locationUrl,
            message: 'Buku baru berhasil ditambahkan.'
        );
    }

    // PUT /api/v1/bukus/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Buku Not Found',
                detail: "Buku dengan ID {$id} tidak ditemukan.",
                status: 404,
                errorSlug: 'buku-not-found'
            );
        }

        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => "required|string|unique:bukus,isbn,{$id}",
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update($validated);

        return $this->successResponse(
            data: new BukuResource($buku),
            message: 'Data buku berhasil diperbarui secara keseluruhan (PUT).'
        );
    }

    // PATCH /api/v1/bukus/{id}
    public function patch(Request $request, int $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Buku Not Found',
                detail: "Buku dengan ID {$id} tidak ditemukan.",
                status: 404,
                errorSlug: 'buku-not-found'
            );
        }

        $validated = $request->validate([
            'kategori_id' => 'sometimes|integer',
            'judul' => 'sometimes|string|max:255',
            'penulis' => 'sometimes|string|max:255',
            'isbn' => "sometimes|string|unique:bukus,isbn,{$id}",
            'stok' => 'sometimes|integer|min:0',
        ]);

        $buku->update($validated);

        return $this->successResponse(
            data: new BukuResource($buku),
            message: 'Atribut buku berhasil diperbarui secara parsial (PATCH).'
        );
    }

    // DELETE /api/v1/bukus/{id}
    public function destroy(int $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Buku Not Found',
                detail: "Buku dengan ID {$id} tidak ditemukan.",
                status: 404,
                errorSlug: 'buku-not-found'
            );
        }

        // Cek peminjaman aktif
        if (method_exists($buku, 'peminjamans') && $buku->peminjamans()->where('status', 'dipinjam')->exists()) {
            return $this->problemResponse(
                title: 'Cannot Delete Borrowed Book',
                detail: "Buku '{$buku->judul}' tidak dapat dihapus karena sedang memiliki transaksi peminjaman aktif.",
                status: 409,
                errorSlug: 'book-has-active-loans'
            );
        }

        $buku->delete();

        return $this->noContentResponse();
    }
}