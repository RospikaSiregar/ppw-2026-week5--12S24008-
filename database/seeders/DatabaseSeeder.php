<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $kategoris = [
            ['kode_kategori' => 'KAT-SI', 'nama_kategori' => 'Sistem Informasi'],
            ['kode_kategori' => 'KAT-PW', 'nama_kategori' => 'Pemrograman Web'],
            ['kode_kategori' => 'KAT-AI', 'nama_kategori' => 'Kecerdasan Buatan'],
            ['kode_kategori' => 'KAT-BD', 'nama_kategori' => 'Basis Data'],
            ['kode_kategori' => 'KAT-JK', 'nama_kategori' => 'Jaringan Komputer'],
        ];

        foreach ($kategoris as $kat) {
            $kategori = Kategori::create($kat);
            Buku::factory(4)->create([
                'kategori_id' => $kategori->id,
            ]);
        }
    }
}
