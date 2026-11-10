<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kategori_id' => $this->kategori_id,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'isbn' => $this->isbn,
            'stok' => $this->stok,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            '_links' => [
                'self' => [
                    'href' => url("/api/v1/bukus/{$this->id}"),
                    'method' => 'GET',
                ],
                'update' => [
                    'href' => url("/api/v1/bukus/{$this->id}"),
                    'method' => 'PUT',
                ],
                'delete' => [
                    'href' => url("/api/v1/bukus/{$this->id}"),
                    'method' => 'DELETE',
                ],
                'borrow' => $this->stok > 0 ? [
                    'href' => url("/api/v1/peminjamans"),
                    'method' => 'POST',
                    'payload' => [
                        'buku_id' => $this->id,
                    ],
                ] : null,
            ],
        ];
    }
}