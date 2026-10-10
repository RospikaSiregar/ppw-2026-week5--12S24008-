<x-layout>
    <x-slot:title>Tambah Buku - SIPUS-Del</x-slot:title>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Form Tambah Buku Baru</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('buku.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}">
                    @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}">
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis') }}">
                        @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit') }}">
                        @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit') }}">
                        @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stok Buku</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}">
                        @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sinopsis</label>
                    <textarea name="sinopsis" class="form-control" rows="3">{{ old('sinopsis') }}</textarea>
                </div>

                <button type="submit" class="btn btn-del">Simpan Buku</button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-layout>