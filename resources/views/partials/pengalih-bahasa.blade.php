{{-- Pengalih bahasa --}}
@php
    // Navbar desktop berlatar gelap (teks putih); menu mobile berlatar terang (teks gelap).
    $latarGelap = $latarGelap ?? true;
    $warnaTeks = $latarGelap ? 'text-white' : 'text-gray-dark';
@endphp
<div class="inline-flex items-center border border-gray-line rounded-full p-1">

    <a href="{{ route('bahasa.ganti', 'id') }}"
       class="{{ app()->getLocale() === 'id' ? 'bg-primary text-white' : $warnaTeks }} font-semibold text-sm px-3 py-1 rounded-full">
        ID
    </a>

    <a href="{{ route('bahasa.ganti', 'en') }}"
       class="{{ app()->getLocale() === 'en' ? 'bg-primary text-white' : $warnaTeks }} font-semibold text-sm px-3 py-1 rounded-full">
        EN
    </a>

</div>
