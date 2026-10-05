@extends('layouts.admin')
@section('title', 'Katalog Produk')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Produk</h4>
                    <a href="{{ route('back-office.produk.create') }}" class="btn btn-primary float-end">
                        Tambah Produk
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarProduk as $item)
                                <tr>
                                    <td>{{ $daftarProduk->firstItem() + $loop->index }}</td>
                                    <td>
                                        @if ($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" style="max-height: 60px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>
                                        {{ $item->kategori?->nama_kategori ?? '-' }}
                                        @if ($item->kategori)
                                            <span class="badge bg-info">{{ $item->kategori->produks_count }} produk</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->hargaRupiah() }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('back-office.produk.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('back-office.produk.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $daftarProduk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection