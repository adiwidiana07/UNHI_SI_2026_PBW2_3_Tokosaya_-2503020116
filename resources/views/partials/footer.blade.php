    <footer class="border-t border-gray-line">
        <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
          <div class="flex flex-wrap -mx-4">
            <!-- Menu 1 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Shop</h3>
              <ul>
                <li><a href="{{ url('/shop') }}" class="hover:text-primary">Shop</a></li>
                <li><a href="{{ url('/product') }}" class="hover:text-primary">Women</a></li>
                <li><a href="{{ url('/shop') }}" class="hover:text-primary">Men</a></li>
                <li><a href="{{ url('/product') }}" class="hover:text-primary">Shoes</a></li>
                <li><a href="{{ url('/product') }}" class="hover:text-primary">Accessories</a></li>
              </ul>
            </div>
            <!-- Menu 2 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Pages</h3>
              <ul>
                <li><a href="{{ url('/shop') }}" class="hover:text-primary">Shop</a></li>
                <li><a href="{{ url('/product') }}" class="hover:text-primary">Product</a></li>
                <li><a href="{{ url('/checkout') }}" class="hover:text-primary">Checkout</a></li>
                <li><a href="{{ url('/404') }}" class="hover:text-primary">404</a></li>
              </ul>
            </div>
            <!-- Menu 3 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Account</h3>
              <ul>
                <li><a href="{{ url('/cart') }}" class="hover:text-primary">Cart</a></li>
                <li><a href="{{ url('/register') }}" class="hover:text-primary">Registration</a></li>
                <li><a href="{{ url('/login') }}" class="hover:text-primary">Login</a></li>
              </ul>
            </div>
            <!-- Social Media -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
              <ul>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('assets/tailstore/img/social_icons/facebook.svg') }}" alt="Facebook" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Facebook</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('assets/tailstore/img/social_icons/twitter.svg') }}" alt="Twitter" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Twitter</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('assets/tailstore/img/social_icons/instagram.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Instagram</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('assets/tailstore/img/social_icons/pinterest.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Pinterest</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('assets/tailstore/img/social_icons/youtube.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">YouTube</a>
                </li>
              </ul>
            </div>
            <!-- Contact Information -->
            <div class="w-full sm:w-2/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
              <p><img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-[60px] mb-4"></p>
              <p>Wibu Store, Jl. Raya Mas, Ubud, Gianyar, Bali</p>
              <p class="text-xl font-bold my-4">Phone: +62 812-3456-7890</p>
              <a href="mailto:halo@wibustore.id" class="underline">Email: halo@wibustore.id</a>
            </div>
          </div>
        </div>

        <!-- Bottom part -->
        <div class="py-6 border-t border-gray-line">
          <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
            <!-- Copyright and Links -->
            <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
              <p class="mb-2 font-bold">&copy; 2026 Wibu Store. All rights reserved by <a
              href="https://github.com/spacemadev" target="_blank" style="color: green;">Maksim M</a> • Distributed by <a href="https://themewagon.com/" target="_blank"
        style="color: green;font-weight: bold;">ThemeWagon</a></p>
              <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
                <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-primary">Terms of Service</a></li>
                <li><a href="#" class="hover:text-primary">FAQ</a></li>
              </ul>
              <p class="text-sm mt-4">Wibu Store adalah toko figure dan manga Jepang di Ubud, Bali. Dari Gunpla, statue karakter, sampai komik lengkap.</p>
            </div>
            <!-- Payment Icons -->
            <div class="w-full lg:w-1/4 text-center lg:text-right">
              <img src="{{ asset('assets/tailstore/img/social_icons/paypal.svg') }}" alt="PayPal" class="inline-block h-8 mr-2">
              <img src="{{ asset('assets/tailstore/img/social_icons/stripe.svg') }}" alt="Stripe" class="inline-block h-8 mr-2">
              <img src="{{ asset('assets/tailstore/img/social_icons/visa.svg') }}" alt="Visa" class="inline-block h-8">
            </div>
          </div>
        </div>
    </footer>