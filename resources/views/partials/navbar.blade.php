    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <!-- Left section: Logo -->
            <a href="{{ url('/') }}" class="flex items-center">
              <div>
                  <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-14 w-auto mr-4">
              </div>
            </a>

            <!-- Hamburger menu (for mobile) -->
            <div class="flex lg:hidden">
                <button id="hamburger" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Center section: Menu -->
            <nav class="hidden lg:flex md:flex-grow justify-center">
              <ul class="flex justify-center space-x-4 text-white">
                  <li><a href="{{ url('/') }}" class="hover:text-secondary font-semibold">{{ __('web.beranda') }}</a></li>
                  <li><a href="{{ route('produk.index') }}" class="hover:text-secondary font-semibold">{{ __('web.produk') }}</a></li>
                  <li><a href="{{ route('kontak') }}" class="hover:text-secondary font-semibold">{{ __('web.kontak') }}</a></li>
              </ul>
            </nav>

            <!-- Right section: Buttons (for desktop) -->
            <div class="hidden lg:flex items-center space-x-4 relative">
              @include('partials.pengalih-bahasa')
              <a href="{{ url('/register') }}"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">{{ __('web.daftar') }}</a>
              <a href="{{ route('back-office.login') }}"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">{{ __('web.masuk') }}</a>
              <div class="relative group cart-wrapper">
                  <a href="{{ url('/cart') }}" >
                      <img src="{{ asset('assets/tailstore/img/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                  </a>
                  <!-- Cart dropdown -->
                  <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                      <div class="space-y-4">
                          <!-- product item -->
                          <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                              <div class="flex items-center">
                                  <img src="{{ asset('assets/tailstore/img/single-product/1.jpg') }}" alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                  <div>
                                      <p class="font-semibold">{{ __('web.keranjang_produk1') }}</p>
                                      <p class="text-sm">{{ __('web.jumlah') }}: 1</p>
                                  </div>
                              </div>
                              <p class="font-semibold">$25.00</p>
                          </div>
                          <!-- product item -->
                          <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('assets/tailstore/img/single-product/2.jpg') }}" alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                <div>
                                    <p class="font-semibold">{{ __('web.keranjang_produk2') }}</p>
                                    <p class="text-sm">{{ __('web.jumlah') }}: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold">$125.00</p>
                        </div>
                      </div>
                      <a href="{{ url('/cart') }}" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">{{ __('web.ke_keranjang') }}</a>
                  </div>
              </div>
              <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                  <img src="{{ asset('assets/tailstore/img/search-icon.svg') }}" alt="Search"
                      class="h-6 w-6 transition-transform transform group-hover:scale-120">
              </a>
              <!-- Search field -->
              <div id="search-field"
                  class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                  <input type="text" class="w-full p-2 border border-gray-300 rounded"
                      placeholder="{{ __('web.cari_produk') }}">
              </div>
          </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <nav id="mobile-menu-placeholder" class="mobile-menu hidden flex flex-col items-center space-y-8 lg:hidden">
      @include('partials.pengalih-bahasa', ['latarGelap' => false])
      <ul class="w-full">
          <li><a href="{{ url('/') }}" class="hover:text-secondary font-bold block py-2">{{ __('web.beranda') }}</a></li>
          <li><a href="{{ route('produk.index') }}" class="hover:text-secondary font-bold block py-2">{{ __('web.produk') }}</a></li>
          <li><a href="{{ route('kontak') }}" class="hover:text-secondary font-bold block py-2">{{ __('web.kontak') }}</a></li>
      </ul>
      <div class="flex flex-col mt-6 space-y-2 items-center">
          <a href="{{ url('/register') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">{{ __('web.daftar') }}</a>
          <a href="{{ url('/login') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">{{ __('web.masuk') }}</a>
          <a href="{{ url('/register') }}"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">{{ __('web.keranjang') }} - {{ __('web.jumlah_keranjang', ['jumlah' => 5]) }}</a>
      </div>
      <!-- Search field -->
      <div
          class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
          <input type="text" class="w-full p-2 border border-gray-300 rounded"
              placeholder="{{ __('web.cari_produk') }}">
      </div>
    </nav>
