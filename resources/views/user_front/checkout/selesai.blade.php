@extends('layouts.app')

@section('title', __('web.terima_kasih'))

@section('content')

<section class="container mx-auto px-4 py-20">
    <div class="text-center">

        {{-- ---- baru di Pertemuan 9: pesan dari tombol bayar ---- --}}
        @include('user_front.partials.pesan')

        <h1 class="text-3xl font-bold mb-4">{{ __('web.terima_kasih') }}</h1>
        <p class="text-gray-txt mb-2">{{ __('web.pesanan_diterima') }}</p>

        <p class="text-lg mb-4">
            {{ __('web.nomor_pesanan') }}:
            <strong class="text-primary">{{ $pesanan->kode_pesanan }}</strong>
        </p>

        {{-- ---- baru di Pertemuan 9: status pembayaran ---- --}}
        <p class="mb-4">@include('user_front.partials.status_bayar')</p>

        @if ($pesanan->sudahLunas())
            <p class="text-gray-txt mb-8">{{ __('web.pembayaran_diterima') }}</p>

        @elseif ($pesanan->status_bayar === 'pending')
            <p class="text-gray-txt mb-4">{{ __('web.pembayaran_diproses') }}</p>

            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="{{ route('checkout.selesai', $pesanan->kode_pesanan) }}"
                   class="border border-primary text-primary font-semibold py-2 px-6 rounded-full">
                    {{ __('web.muat_ulang_status') }}
                </a>

                <form method="POST" action="{{ route('checkout.bayar', $pesanan->kode_pesanan) }}">
                    @csrf
                    <button type="submit"
                            class="bg-primary border border-primary text-white font-semibold py-2 px-6 rounded-full">
                        {{ __('web.buka_halaman_bayar') }}
                    </button>
                </form>
            </div>

        @elseif ($pesanan->bisaDibayar())
            <p class="text-gray-txt mb-4">{{ __('web.pesanan_belum_dibayar') }}</p>

            <form method="POST" action="{{ route('checkout.bayar', $pesanan->kode_pesanan) }}" class="mb-8">
                @csrf
                <button type="submit"
                        class="bg-primary text-white font-semibold py-2 px-6 rounded-full">
                    {{ __('web.coba_bayar_lagi') }}
                </button>
            </form>

        @else
            <p class="text-gray-txt mb-8">{{ __('web.pesanan_batal_bayar') }}</p>
        @endif

        <div class="flex flex-wrap -mx-4 mb-8 text-left">

            {{-- Kotak rincian — sama dengan Pertemuan 7 --}}
            <div class="w-full lg:w-1/2 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">{{ __('web.rincian_pesanan') }}</h2>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.nama_penerima') }}</span>
                        <span class="font-semibold">{{ $pesanan->nama_pemesan }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.nomor_telepon') }}</span>
                        <span class="font-semibold">{{ $pesanan->telepon }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.kota') }}</span>
                        <span class="font-semibold">{{ $pesanan->kota }}</span>
                    </div>

                    <div class="flex justify-between mb-2 pb-2 border-b border-gray-line">
                        <span class="text-gray-txt">{{ __('web.jumlah_barang') }}</span>
                        <span class="font-semibold">{{ $pesanan->detail->sum('jumlah') }} item</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-lg font-bold">{{ __('web.total') }}</span>
                        <span class="text-2xl font-bold text-primary">{{ $pesanan->totalRupiah() }}</span>
                    </div>
                </div>
            </div>

            {{-- ---- baru di Pertemuan 9: menggantikan kotak Langkah Selanjutnya ---- --}}
            <div class="w-full lg:w-1/2 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">{{ __('web.pembayaran') }}</h2>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.status_pembayaran') }}</span>
                        <span class="font-semibold">{{ $pesanan->labelBayar()[0] }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.metode_pembayaran') }}</span>
                        <span class="font-semibold">
                            {{ $pesanan->metode_bayar ? Str::headline($pesanan->metode_bayar) : '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-txt">{{ __('web.waktu_pembayaran') }}</span>
                        <span class="font-semibold">{{ $pesanan->dibayar_pada?->format('d/m/Y H:i') ?? '—' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <a href="{{ route('produk.index') }}"
           class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full inline-block">
            {{ __('web.lanjut_belanja') }}
        </a>

    </div>
</section>

@endsection
