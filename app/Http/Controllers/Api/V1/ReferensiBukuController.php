<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ReferensiBukuController extends Controller
{
    use ApiResponse;

    // GET /api/v1/bukus/{id}/referensi-eksternal (Consumer OpenLibrary API)
    public function show(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse('Resource Not Found', "Buku dengan ID '{$id}' tidak ditemukan.", 404, 'buku-not-found');
        }

        $isbn = str_replace(['-', ' '], '', $buku->isbn);

        try {
            // Panggil REST API publik OpenLibrary dengan timeout 8 detik
            $response = Http::timeout(8)
                ->get("https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data");

            if ($response->failed()) {
                return $this->problemResponse(
                    title: 'Bad Gateway External API Error',
                    detail: 'Gagal terhubung dengan layanan eksternal OpenLibrary API.',
                    status: 502,
                    errorSlug: 'external-api-error'
                );
            }

            $data = $response->json();
            $key = "ISBN:{$isbn}";

            if (!isset($data[$key])) {
                return $this->successResponse(
                    data: [
                        'buku_lokal' => $buku->judul,
                        'isbn' => $buku->isbn,
                        'referensi_eksternal' => null,
                        'catatan' => 'Metadata ISBN tidak ditemukan di OpenLibrary.',
                    ],
                    message: 'Buku lokal ditemukan, tetapi tidak ada metadata eksternal.'
                );
            }

            $ext = $data[$key];

            return $this->successResponse(
                data: [
                    'buku_lokal' => [
                        'id' => $buku->id,
                        'judul' => $buku->judul,
                        'penulis' => $buku->penulis,
                        'isbn' => $buku->isbn,
                    ],
                    'metadata_eksternal' => [
                        'title' => $ext['title'] ?? null,
                        'publish_date' => $ext['publish_date'] ?? null,
                        'number_of_pages' => $ext['number_of_pages'] ?? null,
                        'publishers' => collect($ext['publishers'] ?? [])->pluck('name'),
                        'cover_url' => $ext['cover']['medium'] ?? null,
                        'openlibrary_url' => $ext['url'] ?? null,
                    ],
                ],
                message: 'Data referensi metadata eksternal berhasil diambil dari OpenLibrary API.'
            );
        } catch (\Throwable $e) {
            return $this->problemResponse(
                title: 'Gateway Timeout',
                detail: 'Koneksi ke OpenLibrary API mengalami batas waktu (timeout).',
                status: 504,
                errorSlug: 'gateway-timeout'
            );
        }
    }
}