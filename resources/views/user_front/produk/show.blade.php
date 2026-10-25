@extends('layouts.app')

@section('title', $produk->nama_produk)

@section('content')

    @include('user_front.partials.pesan')

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
                    @if ($produk->stok > 0)
                           <form action="{{ route('keranjang.tambah', $produk) }}" method="POST" class="mb-6">
        @csrf

        <div class="mb-6">
            <label for="jumlah" class="block font-medium mb-2">{{ __('web.jumlah') }}</label>

            <div class="flex items-center">
                <button type="button" onclick="ubahJumlah(-1)"
                        class="w-10 h-10 rounded-full bg-gray-100 font-bold text-xl">&minus;</button>

                <input type="number" name="jumlah" id="jumlah"
                       value="1" min="1" max="{{ $produk->stok }}"
                       class="w-16 text-center border border-gray-300 rounded p-2 mx-2">

                <button type="button" onclick="ubahJumlah(1)"
                        class="w-10 h-10 rounded-full bg-gray-100 font-bold text-xl">+</button>
            </div>
        </div>

        <button type="submit"
                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">
            {{ __('web.tambah_keranjang') }}
        </button>
    </form>

@else

    <div class="bg-gray-100 rounded-lg p-4 mb-6 text-center">
        <p class="font-semibold mb-0">{{ __('web.habis') }}</p>
    </div>
                    @endif
                    @if ($produk->deskripsi)
                        <h2 class="text-xl font-semibold mb-3">{{ __('web.deskripsi_produk') }}</h2>
                        <p class="mb-6">{{ $produk->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@push('scripts')
<script>
    function ubahJumlah(langkah) {
        const kotak = document.getElementById('jumlah');
        const baru  = parseInt(kotak.value) + langkah;
        const batas = parseInt(kotak.max);

        if (baru >= 1 && baru <= batas) {
            kotak.value = baru;
        }
    }
</script>
@endpush
@endsection