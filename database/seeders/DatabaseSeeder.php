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
        User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name' => 'Pustakawan Del',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Kategori Buku (Diisi kode_kategori & nama_kategori)
        $kategori1 = Kategori::firstOrCreate(
            ['kode_kategori' => 'KAT-001'],
            ['nama_kategori' => 'Pemrograman & Software']
        );
        
        $kategori2 = Kategori::firstOrCreate(
            ['kode_kategori' => 'KAT-002'],
            ['nama_kategori' => 'Basis Data & Sistem']
        );

        // 3. Data Buku Uji Coba
        Buku::firstOrCreate(
            ['isbn' => '978-602-04-1234-5'],
            [
                'judul' => 'Pemrograman Web dengan Laravel 11',
                'penulis' => 'Rospika Sarah',
                'penerbit' => 'Informatika Press',
                'tahun_terbit' => 2024,
                'kategori_id' => $kategori1->id,
                'stok' => 5,
                'sinopsis' => 'Buku panduan praktis mempelajari framework Laravel terbaru.'
            ]
        );

        Buku::firstOrCreate(
            ['isbn' => '978-602-04-5678-9'],
            [
                'judul' => 'Sistem Manajemen Basis Data Enterprise',
                'penulis' => 'Siregar',
                'penerbit' => 'Sains Del',
                'tahun_terbit' => 2023,
                'kategori_id' => $kategori2->id,
                'stok' => 3,
                'sinopsis' => 'Prinsip transaksi ACID dan optimasi query database.'
            ]
        );
    }
}