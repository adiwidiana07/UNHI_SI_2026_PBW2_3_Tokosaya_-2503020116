@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')

    <div class="row">

        {{-- Produk yang dipesan --}}
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Produk yang Dipesan</h3></div>

                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th style="width:70px">Gambar</th>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detail as $nomor => $baris)
                                <tr>
                                    <td>{{ $nomor + 1 }}</td>
                                    <td>
                                        @if ($baris->produk && $baris->produk->gambar)
                                            <img src="{{ asset('storage/' . $baris->produk->gambar) }}"
                                                 width="48" height="48" class="rounded"
                                                 style="object-fit:cover">
                                        @else
                                            <span class="text-secondary">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $baris->nama_produk }}</td>
                                    <td>{{ $baris->hargaRupiah() }}</td>
                                    <td>{{ $baris->jumlah }}</td>
                                    <td class="fw-bold">{{ $baris->subtotalRupiah() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="fs-5">{{ $pesanan->totalRupiah() }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">

            {{-- Informasi & ubah status --}}
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title">Informasi Pesanan</h3></div>
                <div class="card-body">
                    <table class="table table-borderless mb-3">
                        <tr><th style="width:140px">Kode</th>
                            <td><code>{{ $pesanan->kode_pesanan }}</code></td></tr>
                        <tr><th>Tanggal</th>
                            <td>{{ $pesanan->created_at->format('d F Y, H:i') }} WITA</td></tr>
                        <tr><th>Status</th>
                            <td><span class="badge {{ $pesanan->warnaStatus() }}">
                                {{ ucfirst($pesanan->status) }}</span></td></tr>
                    </table>

                    <form action="{{ route('back-office.pesanan.status', $pesanan) }}" method="POST"
                          class="d-flex gap-2">
                        @csrf
                        @method('PATCH')

                        <select name="status" class="form-select">
                            @foreach (['baru','diproses','dikirim','selesai','batal'] as $pilihan)
                                <option value="{{ $pilihan }}"
                                    {{ $pesanan->status === $pilihan ? 'selected' : '' }}>
                                    {{ ucfirst($pilihan) }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>

            {{-- Data pengiriman --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">Data Pengiriman</h3></div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th style="width:140px">Nama</th><td>{{ $pesanan->nama_pemesan }}</td></tr>
                        <tr><th>Email</th><td>{{ $pesanan->email }}</td></tr>
                        <tr><th>Telepon</th><td>{{ $pesanan->telepon }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $pesanan->alamat }}</td></tr>
                        <tr><th>Kota</th>
                            <td>{{ $pesanan->kota }} &mdash; {{ $pesanan->kode_pos ?? '-' }}</td></tr>
                        <tr><th>Catatan</th><td>{{ $pesanan->catatan ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection