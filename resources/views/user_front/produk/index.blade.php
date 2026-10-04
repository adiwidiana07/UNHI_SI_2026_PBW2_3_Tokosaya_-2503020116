@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <!-- Shop -->
    <section id="shop" class="py-8">
        <div class="container mx-auto">
            <!-- Top Filter -->
            <div class="flex flex-col md:flex-row justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Show
                        On Sale</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">List
                        View</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Grid
                        View</button>
                </div>
                <div class="flex mt-5 md:mt-0 space-x-4">
                    <div class="relative">
                        <select
                            class="block appearance-none w-full bg-white border hover:border-primary px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Sort by Latest</option>
                            <option>Sort by Popularity</option>
                            <option>Sort by A-Z</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center px-2">
                            <img id="arrow-down" class="h-4 w-4" src="{{ asset('assets/tailstore/img/filter-down-arrow.svg') }}"
                                alt="filter arrow">
                            <img id="arrow-up" class="h-4 w-4 hidden" src="{{ asset('assets/tailstore/img/filter-up-arrow.svg') }}"
                                alt="filter arrow">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Toggle Button for Mobile -->
            <div class="block md:hidden text-center mb-4">
                <button id="products-toggle-filters"
                    class="bg-primary text-white py-2 px-4 rounded-full focus:outline-none">Show Filters</button>
            </div>
            <div class="flex flex-col md:flex-row">
                <!-- Filters -->
                <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                    <!-- Category Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Category</h3>
                        <div class="space-y-2">
                            @forelse ($daftarKategori as $kategori)
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox custom-checkbox">
                                    <span class="ml-2">{{ $kategori->nama_kategori }}</span>
                                </label>
                            @empty
                                <span class="text-sm text-gray-txt">Belum ada kategori.</span>
                            @endforelse
                        </div>
                    </div>
                    <!-- Brand Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Brand</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Bandai</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Good Smile Company</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Shueisha</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">Kodansha</span>
                            </label>
                        </div>
                    </div>
                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-6">Rating</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★★</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★☆</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★☆☆</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Products List -->
                <div class="w-full md:w-3/4 p-4">
                    <!-- Products grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($daftarProduk as $produk)
                            <div class="bg-white p-4 rounded-lg shadow">
                                @if ($produk->gambar)
                                    <a href="{{ route('produk.show', $produk) }}">
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                            class="w-full object-cover mb-4 rounded-lg">
                                    </a>
                                @endif
                                <a href="{{ route('produk.show', $produk) }}"
                                    class="text-lg font-semibold mb-2 block">{{ $produk->nama_produk }}</a>
                                <p class="my-2">{{ $produk->kategori->nama_kategori }}</p>
                                <div class="flex items-center mb-4">
                                    <span class="text-lg font-bold {{ $produk->harga_coret ? 'text-primary' : 'text-gray-900' }}">{{ $produk->hargaRupiah() }}</span>
                                    @if ($produk->hargaCoretRupiah())
                                        <span class="text-sm line-through ml-2">{{ $produk->hargaCoretRupiah() }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('produk.show', $produk) }}"
                                    class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full block text-center">Add
                                    to Cart</a>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-txt">Belum ada produk tersedia.</p>
                        @endforelse
                    </div>
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $daftarProduk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop category description -->
    <section id="shop-category-description" class="py-8">
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Figure &amp; Manga Category</h2>
                <p class="mb-4">
                    Discover our wide range of figures and manga, perfect for every otaku and collector. Whether you're
                    looking for a detailed action figure or a complete manga volume set, we have the perfect item for
                    you. Our collection includes a variety of series, styles, and scales to suit everyone's taste.
                </p>
                <p>
                    Browse through our selection and find your new favorite collectible today. All our products are
                    sourced from trusted brands and are designed to satisfy both collectors and fans. Shop now and
                    elevate your collection with our premium figures and manga.
                </p>
            </div>
        </div>
    </section>

@endsection