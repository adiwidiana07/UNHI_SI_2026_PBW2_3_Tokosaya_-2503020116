@extends('layouts.app')

@section('title', __('web.pembayaran'))

@section('content')

<section class="container mx-auto px-4 py-20">
    <div class="md:w-2/3 mx-auto">

        @include('user_front.partials.pesan')

        <div class="bg-white rounded-lg shadow-lg p-6 text-center">

            <h1 class="text-2xl font-bold mb-4">{{ __('web.menunggu_pembayaran') }}</h1>

            <p class="text-gray-txt mb-2">{{ __('web.pesanan_belum_dibayar') }}</p>
            <p class="text-gray-txt mb-6">{{ __('web.simpan_nomor_bayar') }}</p>

            <p class="text-lg mb-2">
                {{ __('web.nomor_pesanan') }}:
                <strong class="text-primary">{{ $pesanan->kode_pesanan }}</strong>
            </p>

            <p class="mb-6">@include('user_front.partials.status_bayar')</p>

            <p class="text-2xl font-bold mb-8">{{ $pesanan->totalRupiah() }}</p>

            <form method="POST" action="{{ route('checkout.bayar', $pesanan->kode_pesanan) }}">
                @csrf
                <button type="submit"
                        class="bg-primary text-white font-semibold py-3 px-6 rounded-full">
                    {{ __('web.bayar_sekarang') }}
                </button>
            </form>

            <p class="text-gray-txt text-sm mt-6">{{ __('web.batas_waktu_bayar') }}</p>

        </div>
    </div>
</section>

@endsection
