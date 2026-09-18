@extends('layouts.app')

@section('title', 'Kontak')

@section('content')

    <!-- Kontak -->
    <section id="kontak" class="py-10">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4">Hubungi Kami</h1>
                <p class="text-gray-txt">Punya pertanyaan soal figure atau manga? Hubungi Wibu Store, kami siap membantu.</p>
            </div>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Informasi kontak -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-6">Informasi Kontak</h3>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-primary mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold mb-1">Alamat</p>
                                    <p>Wibu Store, Jl. Raya Mas, Ubud, Gianyar, Bali</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-phone-alt text-primary mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold mb-1">Telepon</p>
                                    <p>+62 812-3456-7890</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-envelope text-primary mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold mb-1">Email</p>
                                    <a href="mailto:halo@wibustore.id" class="underline">halo@wibustore.id</a>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock text-primary mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold mb-1">Jam Operasional</p>
                                    <p>Senin - Sabtu: 09.00 - 21.00</p>
                                    <p>Minggu: Tutup</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Formulir kontak -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-6">Kirim Pesan</h3>
                        <form class="space-y-4">
                            <div class="space-y-4 md:flex md:space-x-4 md:space-y-0">
                                <div class="md:flex-1">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" id="name" name="name"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                </div>
                                <div class="md:flex-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700">Subjek</label>
                                <input type="text" id="subject" name="subject"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Pesan</label>
                                <textarea id="message" name="message" rows="5"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                            </div>
                            <div>
                                <button type="submit"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full focus:outline-none">Kirim
                                    Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection