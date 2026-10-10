@extends('layouts.app')

@section('title', $produk->nama_produk)

@section('content')

    <!-- Detail produk -->
    <section id="produk-detail" class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- Gambar produk -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        @if ($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                class="w-full object-cover rounded-lg">
                        @else
                            <div class="flex items-center justify-center h-64 text-gray-txt">
                                {{ __('web.gambar_belum') }}
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Informasi produk -->
                <div class="w-full lg:w-1/2">
                    <h1 class="text-3xl font-bold mb-2">{{ $produk->nama_produk }}</h1>
                    <p class="text-gray-txt mb-6">{{ $produk->kategori->nama_kategori }}</p>
                    <div class="flex items-center mb-6">
                        <span class="text-2xl font-bold text-primary">{{ $produk->hargaRupiah() }}</span>
                        @if ($produk->hargaCoretRupiah())
                            <span class="text-lg line-through text-gray-500 ml-3">{{ $produk->hargaCoretRupiah() }}</span>
                        @endif
                    </div>
                    @if ($produk->stok > 0)
                        <p class="text-sm text-gray-txt mb-4">{{ __('web.tersedia', ['jumlah' => $produk->stok]) }}</p>
                    @else
                        <p class="text-sm text-red-600 mb-4">{{ __('web.habis') }}</p>
                    @endif
                    @if ($produk->deskripsi)
                        <h2 class="text-xl font-semibold mb-3">{{ __('web.deskripsi_produk') }}</h2>
                        <p class="mb-6">{{ $produk->deskripsi }}</p>
                    @endif
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-6 rounded-full">{{ __('web.tambah_keranjang') }}</button>
                </div>
            </div>
        </div>
    </section>

@endsection