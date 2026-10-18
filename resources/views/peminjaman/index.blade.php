<x-layout>
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Riwayat Peminjaman & Pengembalian Buku</h2>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">← Kembali ke Katalog</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kode Pinjam</th>
                            <th>Judul Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $p->kode_pinjam }}</td>
                                <td>{{ $p->buku->judul ?? '-' }}</td>
                                <td>{{ $p->nama_peminjam }} ({{ $p->nim_peminjam }})</td>
                                <td>{{ $p->tanggal_pinjam }}</td>
                                <td>{{ $p->tanggal_jatuh_tempo }}</td>
                                <td>
                                    @if($p->status === 'dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->status === 'dipinjam')
                                        <form action="{{ route('peminjaman.kembali', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Kembalikan buku ini?')">
                                                Kembalikan Buku
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat peminjaman buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end p-3">
                {{ $peminjamans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layout>