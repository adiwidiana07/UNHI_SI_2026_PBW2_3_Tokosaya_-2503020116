@extends('layouts.app')

@section('title', $produk['nama'])

@section('content')

    <!-- Detail produk -->
    <section id="produk-detail" class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- Gambar produk -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        <img src="{{ asset($produk['gambar']) }}" alt="{{ $produk['nama'] }}" class="w-full object-cover rounded-lg">
                    </div>
                </div>
                <!-- Informasi produk -->
                <div class="w-full lg:w-1/2">
                    <h1 class="text-3xl font-bold mb-2">{{ $produk['nama'] }}</h1>
                    <p class="text-gray-txt mb-6">{{ $produk['kategori'] }}</p>
                    <div class="flex items-center mb-6">
                        <span class="text-2xl font-bold text-primary">${{ number_format($produk['harga'], 2) }}</span>
                        @if (! empty($produk['harga_asli']))
                            <span class="text-lg line-through text-gray-500 ml-3">${{ number_format($produk['harga_asli'], 2) }}</span>
                        @endif
                    </div>
                    <button class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-6 rounded-full">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

@endsection