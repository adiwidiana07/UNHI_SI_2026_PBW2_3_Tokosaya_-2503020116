@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Produk</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('back-office.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" id="nama_produk" class="form-control"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select name="kategori_id" id="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($daftarKategori as $kategori)
                                        <option value="{{ $kategori->id }}" @selected(old('kategori_id', $produk->kategori_id) == $kategori->id)>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kode_produk" class="form-label">Kode Produk</label>
                                <input type="text" name="kode_produk" id="kode_produk" class="form-control"
                                    value="{{ old('kode_produk', $produk->kode_produk) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" name="harga" id="harga" class="form-control" step="0.01" min="0"
                                    value="{{ old('harga', $produk->harga) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="harga_coret" class="form-label">Harga Coret</label>
                                <input type="number" name="harga_coret" id="harga_coret" class="form-control" step="0.01" min="0"
                                    value="{{ old('harga_coret', $produk->harga_coret) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" name="stok" id="stok" class="form-control" min="0"
                                    value="{{ old('stok', $produk->stok) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="berat" class="form-label">Berat (gram)</label>
                                <input type="number" name="berat" id="berat" class="form-control" min="0"
                                    value="{{ old('berat', $produk->berat) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="aktif" @selected(old('status', $produk->status) === 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected(old('status', $produk->status) === 'nonaktif')>Nonaktif</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                @if ($produk->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="max-height: 120px;">
                                    </div>
                                @endif
                                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Biarkan kosong bila tidak ingin mengganti gambar.</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('back-office.produk.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection