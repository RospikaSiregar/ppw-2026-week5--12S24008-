<x-layout>
    <x-slot:title>Daftar Buku - SIPUS-Del</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Koleksi Buku</h2>
        <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku Baru</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ISBN</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td>{{ $buku->isbn }}</td>
                            <td><strong>{{ $buku->judul }}</strong></td>
                            <td><span class="badge" style="background-color: #4C1D95;">{{ $buku->kategori->nama_kategori }}</span></td>
                            <td>{{ $buku->penulis }}</td>
                            <td>{{ $buku->stok }}</td>
                            <td>
                                <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data buku yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>
</x-layout>