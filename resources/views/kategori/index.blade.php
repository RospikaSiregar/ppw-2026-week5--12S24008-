<x-layout>
    <x-slot:title>Daftar Kategori - SIPUS-Del</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Kategori Buku</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr>
                            <td>{{ $kategoris->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary">{{ $kategori->kode_kategori }}</span></td>
                            <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                            <td>{{ $kategori->bukus_count }} Buku</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $kategoris->links() }}
            </div>
        </div>
    </div>
</x-layout>