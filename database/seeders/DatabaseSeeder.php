<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pustakawan Utama
        User::factory()->create([
            'name' => 'Pustakawan Del',
            'email' => 'pustakawan@del.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // 2. Data Kategori Buku
        $kategori1 = Kategori::create(['nama' => 'Pemrograman & Software']);
        $kategori2 = Kategori::create(['nama' => 'Basis Data & Sistem']);

        // 3. Data Buku Awal
        Buku::create([
            'isbn' => '978-602-04-1234-5',
            'judul' => 'Pemrograman Web dengan Laravel 11',
            'penulis' => 'Rospika Sarah',
            'penerbit' => 'Informatika Press',
            'tahun_terbit' => 2024,
            'kategori_id' => $kategori1->id,
            'stok' => 5,
            'sinopsis' => 'Buku panduan praktis mempelajari framework Laravel terbaru.'
        ]);

        Buku::create([
            'isbn' => '978-602-04-5678-9',
            'judul' => 'Sistem Manajemen Basis Data Enterprise',
            'penulis' => 'Siregar',
            'penerbit' => 'Sains Del',
            'tahun_terbit' => 2023,
            'kategori_id' => $kategori2->id,
            'stok' => 3,
            'sinopsis' => 'Prinsip transaksi ACID dan optimasi query database.'
        ]);
    }
}