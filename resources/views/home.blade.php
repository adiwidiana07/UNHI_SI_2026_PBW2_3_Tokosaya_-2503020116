@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <!-- Slider -->
    <section id="product-slider">
        <div class="main-slider swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/1.png') }}" alt="Product 1">
                    <div class="swiper-slide-content">
                      <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Wibu Store</h2>
                      <p class="mb-4 text-white md:text-2xl">Selamat datang di toko anime & manga <br>tempatnya koleksi figure terbaik.</p>
                        <a href="{{ route('produk.index') }}"
                            class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                            now</a>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/2.png') }}" alt="Product 2">
                    <div class="swiper-slide-content">
                      <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Figure & Gunpla</h2>
                      <p class="mb-4 text-white md:text-2xl">Koleksi action figure dan gunpla <br>dari series favoritmu.</p>
                        <a href="{{ route('produk.index') }}"
                            class="bg-white hover:bg-transparent text-black hover:text-white font-semibold px-4 py-2 rounded-full inline-block border border-transparent hover:border-white">Shop
                            now</a>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/3.png') }}" alt="Product 3">
                    <div class="swiper-slide-content">
                      <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Manga Lokal & Jepang</h2>
                      <p class="mb-4 text-white md:text-2xl">Lengkapi set manga impianmu <br>dengan koleksi terlengkap.</p>
                        <a href="{{ route('produk.index') }}"
                            class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                            now</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider Pagination -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </section>

    <!-- Product banner section -->
    <section id="product-banners">
        <div class="container mx-auto py-10">
            <div class="flex flex-wrap">
                <!-- Category 1 -->
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('assets/img/cat2.png') }}" alt="Figure" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">Figure</h2>
                            <a href="{{ route('produk.index') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
                <!-- Category 2 -->
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('assets/img/cat1.png') }}" alt="Manga" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="category-text absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4 transition duration-300">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">Manga</h2>
                            <a href="{{ route('produk.index') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
                <!-- Category 3 -->
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('assets/img/cat3.png') }}" alt="Gaming" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light/50"></div>
                        <div
                            class="category-text absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4 transition duration-300">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">Gaming</h2>
                            <a href="{{ route('produk.index') }}"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular product section -->
    <section id="popular-products">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Popular products</h2>
            <div class="flex flex-wrap -mx-4">
                @foreach ($produkPopuler as $produk)
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                        <div class="bg-white p-3 rounded-lg shadow-lg">
                            <a href="{{ route('produk.show', $produk['id']) }}">
                                <img src="{{ asset($produk['gambar']) }}" alt="{{ $produk['nama'] }}" class="w-full object-cover mb-4 rounded-lg">
                            </a>
                            <a href="{{ route('produk.show', $produk['id']) }}" class="text-lg font-semibold mb-2">{{ $produk['nama'] }}</a>
                            <p class="my-2">{{ $produk['kategori'] }}</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">${{ number_format($produk['harga'], 2) }}</span>
                                @if (! empty($produk['harga_asli']))
                                    <span class="text-sm line-through ml-2">${{ number_format($produk['harga_asli'], 2) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('produk.show', $produk['id']) }}" class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full block text-center">Add to Cart</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Latest product section -->
    <section id="latest-products" class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Latest products</h2>
            <div class="flex flex-wrap -mx-4">
                @foreach ($produkTerbaru as $produk)
                    <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                        <div class="bg-white p-3 rounded-lg shadow-lg">
                            <a href="{{ route('produk.show', $produk['id']) }}">
                                <img src="{{ asset($produk['gambar']) }}" alt="{{ $produk['nama'] }}" class="w-full object-cover mb-4 rounded-lg">
                            </a>
                            <a href="{{ route('produk.show', $produk['id']) }}" class="text-lg font-semibold mb-2">{{ $produk['nama'] }}</a>
                            <p class="my-2">{{ $produk['kategori'] }}</p>
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-bold text-primary">${{ number_format($produk['harga'], 2) }}</span>
                                @if (! empty($produk['harga_asli']))
                                    <span class="text-sm line-through ml-2">${{ number_format($produk['harga_asli'], 2) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('produk.show', $produk['id']) }}" class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full block text-center">Add to Cart</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Subscribe section -->
    <section id="subscribe" class="py-6 lg:py-24 bg-white border-t border-gray-line">
      <div class="container mx-auto">
          <div class="flex flex-col items-center rounded-lg p-4 sm:p-0 ">
              <div class="mb-8">
                  <h2 class="text-center text-xl font-bold sm:text-2xl lg:text-left lg:text-3xl">Join our newsletter and <span class="text-primary">get $50 discount</span> for your first order
                  </h2>
              </div>
              <div class="flex flex-col items-center w-96 ">
                  <form class="flex w-full gap-2">
                      <input placeholder="Enter your email address"
                             class="w-full flex-1 rounded-full px-3 py-2 border border-gray-300 text-gray-700 placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary" />
                      <button
                          class="bg-primary border border-primary hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Subscribe</button>
                  </form>
              </div>
          </div>
      </div>
    </section>

@endsection