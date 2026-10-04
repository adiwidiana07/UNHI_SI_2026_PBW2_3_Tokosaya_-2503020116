@extends('layouts.admin')
    @section('title', 'Data Kategori')
    @section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Data Kategori</h4>
                        <a href="{{ route('back-office.kategori.create') }}" class="btn btn-primary float-end">
                            Tambah Kategori
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead> 
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarKategori as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_kategori }}</td>
                                        <td>
                                            <a href="{{ route('back-office.kategori.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                            <form action="{{ route('back-office.kategori.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection