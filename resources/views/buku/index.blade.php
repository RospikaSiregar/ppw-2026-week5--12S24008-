<x-layout>
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Daftar Koleksi Buku</h2>
        </div>
    </div>

    <!-- Kartu Statistik Perpustakaan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Total Judul Buku</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total_judul'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Total Stok Tersedia</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total_stok'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Total Peminjaman</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total_peminjaman'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('buku.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, ISBN, atau penulis..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                    @auth
                        <a href="{{ route('buku.create') }}" class="btn btn-success text-nowrap">+ Tambah Buku</a>
                    @endauth
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Buku & Fitur Pinjam Buku -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ISBN</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $buku)
                            <tr>
                                <td class="ps-3">{{ $buku->isbn }}</td>
                                <td class="fw-bold">{{ $buku->judul }}</td>
                                <td><span class="badge bg-secondary">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>{{ $buku->penulis }}</td>
                                <td>
                                    @if($buku->stok > 0)
                                        <span class="badge bg-success">{{ $buku->stok }} Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Form Pinjam Buku (Fitur Transaksi Peminjaman) -->
                                        @if($buku->stok > 0)
                                            <form action="{{ route('peminjaman.store', $buku->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Pinjam buku {{ $buku->judul }}?')">Pinjam</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Pinjam</button>
                                        @endif

                                        @auth
                                            <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus buku ini?')">Hapus</button>
                                            </form>
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data buku yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>