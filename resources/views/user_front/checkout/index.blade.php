@extends('layouts.app')

@section('title', __('web.checkout'))

@section('content')

<section class="container mx-auto px-4 py-12">

    <h1 class="text-3xl font-bold mb-2">{{ __('web.checkout') }}</h1>
    <p class="text-gray-txt mb-8">{{ __('web.checkout_sub') }}</p>

    {{-- Daftar kesalahan --}}
    @if ($errors->any())
        <div class="bg-white border border-primary rounded-lg p-4 mb-6">
            <p class="text-primary font-semibold mb-2">{{ __('web.periksa_isian') }}</p>
            <ul class="text-primary text-sm mb-0">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('user_front.partials.pesan')

    <form action="{{ route('checkout.proses') }}" method="POST">
        @csrf

        <div class="flex flex-wrap -mx-4">

            {{-- Kolom kiri: formulir --}}
            <div class="w-full lg:w-1/2 px-4 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">

                    <h2 class="text-xl font-bold mb-4">{{ __('web.data_pengiriman') }}</h2>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.nama_penerima') }}</label>
                        <input type="text" name="nama_pemesan"
                               value="{{ old('nama_pemesan') }}"
                               class="w-full p-2 border rounded @error('nama_pemesan') border-primary @else border-gray-300 @enderror">
                        @error('nama_pemesan')
                            <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="w-full p-2 border rounded @error('email') border-primary @else border-gray-300 @enderror">
                        @error('email')
                            <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.nomor_telepon') }}</label>
                        <input type="text" name="telepon"
                               value="{{ old('telepon') }}"
                               placeholder="08xx xxxx xxxx"
                               class="w-full p-2 border rounded @error('telepon') border-primary @else border-gray-300 @enderror">
                        @error('telepon')
                            <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.alamat_lengkap') }}</label>
                        <textarea name="alamat" rows="3"
                                  placeholder="Nama jalan, nomor rumah, RT/RW"
                                  class="w-full p-2 border rounded @error('alamat') border-primary @else border-gray-300 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full sm:w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block font-medium mb-2">{{ __('web.kota') }}</label>
                                <input type="text" name="kota"
                                       value="{{ old('kota') }}"
                                       class="w-full p-2 border rounded @error('kota') border-primary @else border-gray-300 @enderror">
                                @error('kota')
                                    <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="w-full sm:w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block font-medium mb-2">{{ __('web.kode_pos') }}</label>
                                <input type="text" name="kode_pos"
                                       value="{{ old('kode_pos') }}"
                                       class="w-full p-2 border border-gray-300 rounded">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.catatan_penjual') }}</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full p-2 border border-gray-300 rounded">{{ old('catatan') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Kolom kanan: ringkasan --}}
            <div class="w-full lg:w-1/2 px-4 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">

                    <h2 class="text-xl font-bold mb-4">{{ __('web.ringkasan_pesanan') }}</h2>

                    @foreach ($daftarItem as $item)
                        <div class="flex items-center mb-4 pb-4 border-b border-gray-line">
                            @if ($item->produk->gambar)
                                <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                     class="w-16 h-16 object-cover rounded mr-4">
                            @endif

                            <div class="flex-1">
                                <p class="font-semibold mb-0">{{ $item->produk->nama_produk }}</p>
                                <p class="text-sm text-gray-txt mb-0">{{ $item->jumlah }} x barang</p>
                            </div>

                            <p class="font-bold mb-0">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-txt">{{ __('web.subtotal') }}</span>
                        <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between mb-4 pb-4 border-b border-gray-line">
                        <span class="text-gray-txt">{{ __('web.ongkos_kirim') }}</span>
                        <span class="font-semibold">{{ __('web.dihitung_nanti') }}</span>
                    </div>

                    <div class="flex justify-between mb-6">
                        <span class="text-lg font-bold">{{ __('web.total') }}</span>
                        <span class="text-2xl font-bold text-primary">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit"
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">
                        {{ __('web.buat_pesanan') }}
                    </button>

                </div>
            </div>

        </div>
    </form>

</section>

@endsection