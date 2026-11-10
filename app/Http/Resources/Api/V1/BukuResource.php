<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hasStock = $this->stok > 0;

        $links = [
            'self' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'GET',
            ],
            'update_full' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'PUT',
            ],
            'update_partial' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'PATCH',
            ],
            'delete' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'DELETE',
            ],
        ];

        // HATEOAS: Tautan 'pinjam_buku' HANYA muncul jika stok > 0
        if ($hasStock) {
            $links['pinjam_buku'] = [
                'href' => url("/api/v1/peminjamans"),
                'method' => 'POST',
            ];
        }

        return [
            'id' => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori_nama' => $this->whenLoaded('kategori', fn () => $this->kategori->nama_kategori),
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'isbn' => $this->isbn,
            'stok' => (int) $this->stok,
            'status_label' => $hasStock ? 'AVAILABLE' : 'OUT_OF_STOCK',
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            '_links' => $links,
        ];
    }
}