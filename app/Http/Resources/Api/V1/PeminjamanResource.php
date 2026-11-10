<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isDipinjam = $this->status === 'dipinjam';

        $links = [
            'self' => [
                'href' => url("/api/v1/peminjamans?nim={$this->nim_peminjam}"),
                'method' => 'GET',
            ],
        ];

        // HATEOAS: Tautan 'kembalikan_buku' HANYA muncul jika status masih 'dipinjam'
        if ($isDipinjam) {
            $links['kembalikan_buku'] = [
                'href' => url("/api/v1/peminjamans/{$this->id}/kembalikan"),
                'method' => 'PATCH',
            ];
        }

        return [
            'id' => $this->id,
            'buku_id' => $this->buku_id,
            'buku' => $this->whenLoaded('buku', fn () => [
                'judul' => $this->buku->judul,
                'isbn' => $this->buku->isbn,
            ]),
            'nim_peminjam' => $this->nim_peminjam,
            'nama_peminjam' => $this->nama_peminjam,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            '_links' => $links,
        ];
    }
}