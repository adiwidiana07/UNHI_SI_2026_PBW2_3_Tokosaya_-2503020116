@extends('layouts.app')

@section('title', __('web.keranjang_belanja'))

@section('content')

    <section class="container mx-auto px-4 py-12">

        @include('user_front.partials.pesan')

        {{-- Kalau keranjang kosong, tampilkan pesan ramah lalu berhenti --}}
        @if ($daftarItem->isEmpty())

            <div class="text-center py-20">
                <h1 class="text-3xl font-bold mb-4">{{ __('web.keranjang_belanja') }}</h1>
                <p class="text-gray-txt mb-8">
                    {{ __('web.keranjang_kosong') }}<br>
                    {{ __('web.silakan_pilih') }}
                </p>
                <a href="{{ route('produk.index') }}"
                    class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full inline-block">
                    {{ __('web.mulai_belanja') }}
                </a>
            </div>

        @else

            <h1 class="text-3xl font-bold mb-2">{{ __('web.keranjang_belanja') }}</h1>
            <p class="text-gray-txt mb-8">
                {{ __('web.produk_di_keranjang', ['jumlah' => $daftarItem->count()]) }}
            </p>

            {{-- Daftar item --}}
            @foreach ($daftarItem as $item)
                <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                    <div class="flex items-center">

                        @if ($item->produk->gambar)
                            <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                alt="{{ $item->produk->nama_produk }}" class="w-16 h-16 object-cover rounded mr-4">
                        @endif

                        <div class="flex-1">
                            <a href="{{ route('produk.show', $item->produk) }}"
                                class="font-semibold text-lg">{{ $item->produk->nama_produk }}</a>
                            <p class="text-sm text-gray-txt">{{ $item->produk->kategori->nama_kategori }}</p>
                            <p class="text-primary font-bold">{{ $item->produk->hargaRupiah() }}</p>
                        </div>

                        {{-- Pengatur jumlah --}}
                        <form action="{{ route('keranjang.ubah', $item->produk) }}" method="POST"
                            class="flex items-center mr-6">
                            @csrf
                            @method('PATCH')

                            <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="0"
                                max="{{ $item->produk->stok }}" onchange="this.form.submit()"
                                class="w-12 text-center border border-gray-300 rounded p-2 mx-2">

                            <button type="submit" class="text-primary font-semibold text-sm">
                                {{ __('web.perbarui') }}
                            </button>
                        </form>

                        <div class="text-right mr-6">
                            <p class="text-sm text-gray-txt">{{ __('web.subtotal') }}</p>
                            <p class="font-bold text-lg">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Tombol hapus --}}
                        <form action="{{ route('keranjang.hapus', $item->produk) }}" method="POST"
                            onsubmit="return confirm('Hapus {{ $item->produk->nama_produk }} dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-primary font-semibold ml-4">
                                {{ __('web.hapus') }}
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach

            {{-- Bagian bawah --}}
            <div class="flex flex-wrap -mx-4 mt-6">

                <div class="w-full lg:w-1/2 px-4 mb-4">
                    <a href="{{ route('produk.index') }}"
                        class="bg-white border border-primary text-primary font-semibold py-2 px-4 rounded-full inline-block">
                        &larr; {{ __('web.lanjut_belanja') }}
                    </a>

                    <form action="{{ route('keranjang.kosongkan') }}" method="POST" class="mt-4"
                        onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-txt font-semibold">
                            {{ __('web.kosongkan') }}
                        </button>
                    </form>
                </div>

                <div class="w-full lg:w-1/2 px-4">
                    <div class="bg-white rounded-lg shadow-lg p-6">

                        <h2 class="text-xl font-bold mb-4">{{ __('web.ringkasan') }}</h2>

                        <div class="flex justify-between mb-2">
                            <span class="text-gray-txt">{{ __('web.total_item') }}</span>
                            <span class="font-semibold">{{ $totalItem }} item</span>
                        </div>

                        <div class="flex justify-between mb-4 pb-4 border-b border-gray-line">
                            <span class="text-gray-txt">{{ __('web.subtotal') }}</span>
                            <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between mb-6">
                            <span class="text-lg font-bold">{{ __('web.total') }}</span>
                            <span class="text-2xl font-bold text-primary">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Halaman tujuannya dibuat di Pertemuan 7 --}}
                        <a href="#"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full block text-center">
                            {{ __('web.lanjut_checkout') }}
                        </a>

                        <p class="text-sm text-gray-txt text-center mt-2 mb-0">
                            {{ __('web.ongkir_nanti') }}
                        </p>

                    </div>
                </div>

            </div>

        @endif

    </section>

@endsection
