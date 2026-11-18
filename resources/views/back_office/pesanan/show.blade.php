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
                            {{-- Hanya kenyamanan; aturan sesungguhnya ditegakkan di controller --}}
                            @foreach (['baru','diproses','dikirim','selesai','batal'] as $pilihan)
                                @php $terkunci = in_array($pilihan, ['dikirim', 'selesai'], true) && ! $pesanan->sudahLunas(); @endphp
                                <option value="{{ $pilihan }}"
                                    {{ $pesanan->status === $pilihan ? 'selected' : '' }}
                                    {{ $terkunci ? 'disabled' : '' }}>
                                    {{ ucfirst($pilihan) }} {{ $terkunci ? '(belum lunas)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div class="card mb-3">
                <div class="card-header"><h3 class="card-title mb-0">Pembayaran</h3></div>

                <div class="card-body">
                    @php [$label, $jenis] = $pesanan->labelBayar(); @endphp

                    <dl class="row mb-0">
                        <dt class="col-sm-5">Status</dt>
                        <dd class="col-sm-7">
                            <span class="badge text-bg-{{ $jenis }}">{{ $label }}</span>
                        </dd>

                        <dt class="col-sm-5">Metode</dt>
                        <dd class="col-sm-7">{{ $pesanan->metode_bayar ?? '—' }}</dd>

                        <dt class="col-sm-5">Waktu Bayar</dt>
                        <dd class="col-sm-7">{{ $pesanan->dibayar_pada?->format('d/m/Y H:i') ?? '—' }}</dd>

                        <dt class="col-sm-5">Order ID Midtrans</dt>
                        <dd class="col-sm-7"><code>{{ $pesanan->midtrans_order_id ?? '—' }}</code></dd>

                        <dt class="col-sm-5">Stok Dikembalikan</dt>
                        <dd class="col-sm-7">{{ $pesanan->stok_dikembalikan ? 'Ya' : 'Tidak' }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Data pengiriman --}}
            <div class="card mb-3">
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

    {{-- Riwayat notifikasi, termasuk yang tanda tangannya palsu --}}
    <div class="card mb-3">
        <div class="card-header"><h3 class="card-title mb-0">Riwayat Notifikasi Midtrans</h3></div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Status Transaksi</th>
                            <th>Fraud</th>
                            <th>Metode</th>
                            <th>Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan->pembayaran as $catatan)
                            <tr>
                                <td>{{ $catatan->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $catatan->transaction_status ?? '—' }}</td>
                                <td>{{ $catatan->fraud_status ?? '—' }}</td>
                                <td>{{ $catatan->payment_type ?? '—' }}</td>
                                <td>
                                    @if ($catatan->signature_sah)
                                        <span class="badge text-bg-success">Sah</span>
                                    @else
                                        <span class="badge text-bg-danger">Tidak Sah</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-3">
                                    Belum ada notifikasi masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection