@if (session('sukses'))
    <div class="bg-white border border-gray-line rounded-lg p-4 mb-6">
        <p class="text-primary font-semibold mb-0">{{ session('sukses') }}</p>
    </div>
@endif

@if (session('gagal'))
    <div class="bg-gray-100 border border-gray-line rounded-lg p-4 mb-6">
        <p class="font-semibold mb-0">{{ session('gagal') }}</p>
    </div>
@endif
