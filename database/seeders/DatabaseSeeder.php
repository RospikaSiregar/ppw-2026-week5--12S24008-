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
        // 1. Seeder Pustakawan
        User::create([
            'name'     => 'Pustakawan Del',
            'email'    => 'pustakawan@del.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // // 2. Seeder Kategori Buku
        $kategori1 = Kategori::create(['kode_kategori' => 'KAT-01', 'nama_kategori' => 'Pemrograman & Software']);
        $kategori2 = Kategori::create(['kode_kategori' => 'KAT-02', 'nama_kategori' => 'Basis Data & Sistem']);
        $kategori3 = Kategori::create(['kode_kategori' => 'KAT-03', 'nama_kategori' => 'Jaringan & Keamanan']);
        $kategori4 = Kategori::create(['kode_kategori' => 'KAT-04', 'nama_kategori' => 'Kecerdasan Buatan']);
        $kategori5 = Kategori::create(['kode_kategori' => 'KAT-05', 'nama_kategori' => 'Sains & Teknik']);

        // 3. Seeder 30 Daftar Buku
        $dataBuku = [
            // --- Pemrograman & Software ---
            [
                'isbn' => '978-602-04-1234-5',
                'judul' => 'Pemrograman Web Modern dengan Laravel 11',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Dr. Budi Raharjo',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2024,
                'stok' => 5,
                'sinopsis' => 'Panduan komprehensif membangun aplikasi web tingkat lanjut menggunakan kerangka kerja Laravel 11.'
            ],
            [
                'isbn' => '978-602-04-1234-6',
                'judul' => 'Pemrograman Berorientasi Objek dengan Java',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Ir. Rinaldi Munir, M.T.',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2023,
                'stok' => 8,
                'sinopsis' => 'Konsep dasar Object-Oriented Programming (OOP) menggunakan sintaksis bahasa Java.'
            ],
            [
                'isbn' => '978-602-04-1234-7',
                'judul' => 'Clean Code: Refactoring dan Arsitektur Bersih',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2021,
                'stok' => 4,
                'sinopsis' => 'Prinsip-prinsip penulisan kode terstruktur, bersih, dan mudah dirawat untuk pengembang profesional.'
            ],
            [
                'isbn' => '978-602-04-1234-8',
                'judul' => 'Arsitektur Microservices dengan Spring Boot',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Ahmad Hanafi, Ph.D.',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2023,
                'stok' => 6,
                'sinopsis' => 'Membangun ekosistem backend terdistribusi yang scalable dan terisolasi dengan baik.'
            ],
            [
                'isbn' => '978-602-04-1234-9',
                'judul' => 'Pengembangan Aplikasi Mobile dengan Flutter',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Asep Sahbidin, M.Kom.',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2024,
                'stok' => 7,
                'sinopsis' => 'Membuat aplikasi cross-platform untuk Android dan iOS menggunakan Dart dan Flutter.'
            ],
            [
                'isbn' => '978-602-04-1235-0',
                'judul' => 'Fullstack JavaScript dengan Node.js dan React',
                'kategori_id' => $kategori1->id,
                'penulis' => 'Bambang Hariyanto',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => 2023,
                'stok' => 5,
                'sinopsis' => 'Pengembangan aplikasi web berbasis RESTful API menggunakan Node.js, Express, dan React.'
            ],

            // --- Basis Data & Sistem ---
            [
                'isbn' => '978-602-04-5678-9',
                'judul' => 'Sistem Manajemen Basis Data Enterprise',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Prof. Jogiyanto H.M.',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2022,
                'stok' => 7,
                'sinopsis' => 'Prinsip mendasar perancangan ERD, normalisasi, dan optimasi SQL Query pada relational database.'
            ],
            [
                'isbn' => '978-602-04-5678-0',
                'judul' => 'Desain dan Optimasi Query PostgreSQL',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Eko Kurniawan Khannedy',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => 2023,
                'stok' => 5,
                'sinopsis' => 'Trik melakukan index tuning, penanganan transaksi ACID, dan manajemen konkurensi di PostgreSQL.'
            ],
            [
                'isbn' => '978-602-04-5678-1',
                'judul' => 'NoSQL Database: MongoDB dan Redis',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Dian Wahyudi, M.Kom.',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2022,
                'stok' => 3,
                'sinopsis' => 'Implementasi basis data non-relasional untuk pencatatan log skala besar dan caching memory.'
            ],
            [
                'isbn' => '978-602-04-5678-2',
                'judul' => 'Analisis dan Perancangan Sistem Informasi',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Dr. Rosa Ariani Sukamto',
                'penerbit' => 'Modula',
                'tahun_terbit' => 2021,
                'stok' => 10,
                'sinopsis' => 'Metodologi pengembangan sistem informasi menggunakan standar UML dan pemodelan BPMN.'
            ],
            [
                'isbn' => '978-602-04-5678-3',
                'judul' => 'Data Warehousing dan Business Intelligence',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Dr. Taufik Hidayat',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2022,
                'stok' => 4,
                'sinopsis' => 'Konsep perancangan ETL, star schema, dan dashboard analitik untuk pengambilan keputusan.'
            ],
            [
                'isbn' => '978-602-04-5678-4',
                'judul' => 'Administrasi Database Microsoft SQL Server',
                'kategori_id' => $kategori2->id,
                'penulis' => 'Harianto Kristanto',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => 2020,
                'stok' => 6,
                'sinopsis' => 'Panduan backup, restore, keamanan perizinan, dan konfigurasi skema SQL Server.'
            ],

            // --- Jaringan & Keamanan ---
            [
                'isbn' => '978-602-04-9012-1',
                'judul' => 'Jaringan Komputer dan Analisis Protokol',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Andrew S. Tanenbaum',
                'penerbit' => 'Pearson Education',
                'tahun_terbit' => 2020,
                'stok' => 6,
                'sinopsis' => 'Konsep dasar layer OSI, TCP/IP, serta teknik inspeksi paket menggunakan Wireshark.'
            ],
            [
                'isbn' => '978-602-04-9012-2',
                'judul' => 'Kriptografi dan Keamanan Siber Modern',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Dr. Eng. Onno W. Purbo',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2023,
                'stok' => 4,
                'sinopsis' => 'Teknik enkripsi simetris, asimetris, pengamanan token JWT, dan proteksi integritas data.'
            ],
            [
                'isbn' => '978-602-04-9012-3',
                'judul' => 'Ethical Hacking dan Penetration Testing',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Budi Susanto, S.T., M.T.',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => 2024,
                'stok' => 5,
                'sinopsis' => 'Metodologi audit keamanan aplikasi web dan deteksi celah kerentanan sistem.'
            ],
            [
                'isbn' => '978-602-04-9012-4',
                'judul' => 'Administrasi Server Linux Ubuntu',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Rahmat M. Samik-Ibrahim',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2022,
                'stok' => 7,
                'sinopsis' => 'Manajemen layanan server, konfigurasi Nginx/Apache, dan otomatisasi deployment.'
            ],
            [
                'isbn' => '978-602-04-9012-5',
                'judul' => 'MikroTik RouterOS untuk Binaan Jaringan',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Iwan Sofana, S.T.',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2021,
                'stok' => 8,
                'sinopsis' => 'Konfigurasi routing, firewall, bandwidth management, dan hotspot MikroTik.'
            ],
            [
                'isbn' => '978-602-04-9012-6',
                'judul' => 'Cloud Computing dan AWS Architecture',
                'kategori_id' => $kategori3->id,
                'penulis' => 'Dr. Irwan Setiawan',
                'penerbit' => 'Pearson',
                'tahun_terbit' => 2023,
                'stok' => 5,
                'sinopsis' => 'Pengenalan infrastruktur awan, EC2 instance, S3 storage, dan deployment terdistribusi.'
            ],

            // --- Kecerdasan Buatan ---
            [
                'isbn' => '978-602-04-3456-1',
                'judul' => 'Kecerdasan Buatan: Konsep dan Algoritma',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Stuart Russell & Peter Norvig',
                'penerbit' => 'Pearson',
                'tahun_terbit' => 2022,
                'stok' => 5,
                'sinopsis' => 'Dasar pencarian jalur A*, algoritma CSP, dan pembelajaran mesin modern.'
            ],
            [
                'isbn' => '978-602-04-3456-2',
                'judul' => 'Machine Learning dengan Python dan Scikit-Learn',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Dr. Indra Budi, M.Kom.',
                'penerbit' => 'Penerbit ITB',
                'tahun_terbit' => 2023,
                'stok' => 8,
                'sinopsis' => 'Penerapan algoritma supervised dan unsupervised learning untuk klasifikasi data.'
            ],
            [
                'isbn' => '978-602-04-3456-3',
                'judul' => 'Deep Learning dan Jaringan Saraf Tiruan',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Ian Goodfellow',
                'penerbit' => 'MIT Press',
                'tahun_terbit' => 2021,
                'stok' => 3,
                'sinopsis' => 'Arsitektur Convolutional Neural Network (CNN) dan Recurrent Neural Network (RNN).'
            ],
            [
                'isbn' => '978-602-04-3456-4',
                'judul' => 'Natural Language Processing Bahasa Indonesia',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Prof. Dr. Ayu Purwarianti',
                'penerbit' => 'Penerbit ITB',
                'tahun_terbit' => 2024,
                'stok' => 6,
                'sinopsis' => 'Teknik pemrosesan teks, analisis sentimen, dan ekstraksi entitas pada dokumen Indonesia.'
            ],
            [
                'isbn' => '978-602-04-3456-5',
                'judul' => 'Computer Vision dan Pengolahan Citra Digital',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Dr. Agus Harjoko',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2022,
                'stok' => 4,
                'sinopsis' => 'Deteksi objek, ekstraksi fitur citra, dan pemrosesan gambar menggunakan OpenCV.'
            ],
            [
                'isbn' => '978-602-04-3456-6',
                'judul' => 'Pengantar Generative AI dan LLM',
                'kategori_id' => $kategori4->id,
                'penulis' => 'Dr. Ir. Lukito Edi Nugroho',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2024,
                'stok' => 5,
                'sinopsis' => 'Konsep arsitektur Transformer, prompt engineering, dan penerapan model bahasa besar.'
            ],

            // --- Sains & Teknik ---
            [
                'isbn' => '978-602-04-7890-1',
                'judul' => 'Matematika Diskrit untuk Ilmu Komputer',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Dr. Jong Jek Siang',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2020,
                'stok' => 12,
                'sinopsis' => 'Teori graf, kombinatorika, logika matematika, dan relasi rekursif dalam komputasi.'
            ],
            [
                'isbn' => '978-602-04-7890-2',
                'judul' => 'Probabilitas dan Statistik Rekayasa',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Dr. Ir. Bob Triadi',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2021,
                'stok' => 9,
                'sinopsis' => 'Pengujian hipotesis, distribusi peluang, dan regresi linier untuk analisis data.'
            ],
            [
                'isbn' => '978-602-04-7890-3',
                'judul' => 'Metode Penelitian Sistem Informasi',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Prof. Dr. Sugiyono',
                'penerbit' => 'Alfabeta',
                'tahun_terbit' => 2022,
                'stok' => 11,
                'sinopsis' => 'Metodologi penelitian kualitatif, kuantitatif, dan rancangan instrumen survei.'
            ],
            [
                'isbn' => '978-602-04-7890-4',
                'judul' => 'Manajemen Proyek TI Berbasis PMBOK',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Ir. Chandra Pardede, M.Sc.',
                'penerbit' => 'Gava Media',
                'tahun_terbit' => 2023,
                'stok' => 7,
                'sinopsis' => 'Panduan pengelolaan scope, jadwal, biaya, dan risiko pada proyek pengembangan perangkat lunak.'
            ],
            [
                'isbn' => '978-602-04-7890-5',
                'judul' => 'Tata Kelola Teknologi Informasi (IT Governance)',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Dr. Widodo Budiharto',
                'penerbit' => 'Andi Publisher',
                'tahun_terbit' => 2021,
                'stok' => 6,
                'sinopsis' => 'Kerangka kerja COBIT dan ITIL untuk penyelarasan strategi bisnis dan teknologi informasi.'
            ],
            [
                'isbn' => '978-602-04-7890-6',
                'judul' => 'Etika Profesi dan Hukum Siber (Cyber Law)',
                'kategori_id' => $kategori5->id,
                'penulis' => 'Dr. Ahmad M. Ramli',
                'penerbit' => 'Refika Aditama',
                'tahun_terbit' => 2022,
                'stok' => 8,
                'sinopsis' => 'Aspek legalitas transaksi elektronik, perlindungan data pribadi, dan hak kekayaan intelektual.'
            ]
        ];

        foreach ($dataBuku as $buku) {
            Buku::create($buku);
        }
    }
}