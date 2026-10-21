@extends('layouts.app')

@section('title', __('web.terima_kasih'))

@section('content')

<section class="container mx-auto px-4 py-20">
    <div class="text-center">

        <h1 class="text-3xl font-bold mb-4">{{ __('web.terima_kasih') }}</h1>
        <p class="text-gray-txt mb-2">{{ __('web.pesanan_diterima') }}</p>

        <p class="text-lg mb-8">
            {{ __('web.nomor_pesanan') }}:
            <strong class="text-primary">{{ $pesanan->kode_pesanan }}</strong>
        </p>

        <div class="flex flex-wrap -mx-4 mb-8">

            <div class="w-full lg:w-1/2 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-lg p-6 text-left">
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

            <div class="w-full lg:w-1/2 px-4 mb-4">
                <div class="bg-gray-100 rounded-lg p-6 text-left">
                    <h2 class="text-xl font-bold mb-4">{{ __('web.langkah_lanjut') }}</h2>
                    <p class="text-gray-txt mb-2">{{ __('web.simpan_nomor') }}</p>
                    <p class="text-gray-txt mb-0">{{ __('web.akan_dihubungi') }}</p>
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