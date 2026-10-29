@extends('layouts.admin')

@section('title', 'Data Pesanan')
@section('page-title', 'Pesanan')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Daftar Pesanan</h3>
            <span class="text-secondary">{{ $daftarPesanan->total() }} pesanan masuk</span>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Kode Pesanan</th>
                        <th>Pemesan</th>
                        <th>Kota</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($daftarPesanan as $nomor => $pesanan)
                        <tr>
                            <td>{{ $daftarPesanan->firstItem() + $nomor }}</td>
                            <td><code>{{ $pesanan->kode_pesanan }}</code></td>
                            <td>{{ $pesanan->nama_pemesan }}</td>
                            <td>{{ $pesanan->kota }}</td>
                            <td>{{ $pesanan->detail_sum_jumlah }} item</td>
                            <td class="fw-bold">{{ $pesanan->totalRupiah() }}</td>
                            <td>
                                <span class="badge {{ $pesanan->warnaStatus() }}">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>
                            <td>{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('back-office.pesanan.show', $pesanan) }}"
                                   class="btn btn-primary btn-sm">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-secondary">
                                Belum ada pesanan yang masuk.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="card-footer">{{ $daftarPesanan->links('pagination::bootstrap-5') }}</div>
    </div>

@endsection