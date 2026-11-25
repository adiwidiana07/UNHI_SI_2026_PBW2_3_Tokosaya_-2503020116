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

    <form action="{{ route('checkout.proses') }}" method="POST" id="form-checkout">
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

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.provinsi') }}</label>
                        <select name="provinsi_id" id="provinsi_id"
                                class="w-full p-2 border rounded @error('provinsi_id') border-primary @else border-gray-300 @enderror">
                            <option value="">{{ __('web.pilih_provinsi') }}</option>
                            @foreach ($daftarProvinsi as $provinsi)
                                <option value="{{ $provinsi->id }}" @selected(old('provinsi_id') == $provinsi->id)>
                                    {{ $provinsi->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('provinsi_id')
                            <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full sm:w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block font-medium mb-2">{{ __('web.kota') }}</label>
                                <select name="kota_id" id="kota_id" data-lama="{{ old('kota_id') }}" disabled
                                        class="w-full p-2 border rounded @error('kota_id') border-primary @else border-gray-300 @enderror">
                                    <option value="">{{ __('web.pilih_kota') }}</option>
                                </select>
                                @error('kota_id')
                                    <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="w-full sm:w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block font-medium mb-2">{{ __('web.kecamatan') }}</label>
                                <select name="kecamatan_id" id="kecamatan_id" data-lama="{{ old('kecamatan_id') }}" disabled
                                        class="w-full p-2 border rounded @error('kecamatan_id') border-primary @else border-gray-300 @enderror">
                                    <option value="">{{ __('web.pilih_kecamatan') }}</option>
                                </select>
                                @error('kecamatan_id')
                                    <p class="text-primary text-sm mt-1 mb-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">{{ __('web.kode_pos') }}</label>
                        <input type="text" name="kode_pos" id="kode_pos"
                               value="{{ old('kode_pos') }}"
                               class="w-full p-2 border border-gray-300 rounded">
                    </div>

                    {{-- ---- baru di Pertemuan 11: pilihan kurir ---- --}}
                    <h2 class="text-xl font-bold mb-4">{{ __('web.pengiriman') }}</h2>

                    <div id="daftar-layanan" data-lama="{{ old('layanan') }}" class="mb-2 text-gray-txt">
                        {{ __('web.pilih_alamat_dulu') }}
                    </div>
                    @error('layanan')
                        <p class="text-primary text-sm mt-1 mb-4">{{ $message }}</p>
                    @enderror

                    <p class="text-sm text-gray-txt mb-4">
                        {{ __('web.berat_total') }}: {{ number_format($totalBerat, 0, ',', '.') }} gram
                    </p>

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
                        <span class="font-semibold" id="tampil-ongkir">—</span>
                    </div>

                    <div class="flex justify-between mb-6">
                        <span class="text-lg font-bold">{{ __('web.total') }}</span>
                        <span class="text-2xl font-bold text-primary" id="tampil-total" data-subtotal="{{ (int) round($totalHarga) }}">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Js::from, bukan direktif json Blade: direktif itu memecah isinya
    // di setiap koma, sehingga array dengan banyak isi terpotong dan PHP galat
    const teks = {{ Js::from([
        'kota'      => __('web.pilih_kota'),
        'kecamatan' => __('web.pilih_kecamatan'),
        'alamat'    => __('web.pilih_alamat_dulu'),
        'memuat'    => __('web.memuat'),
        'kosong'    => __('web.tidak_ada_layanan'),
        'estimasi'  => __('web.estimasi'),
    ]) }};
    const alamat = {{ Js::from([
        'kota'      => url('ongkir/kota'),
        'kecamatan' => url('ongkir/kecamatan'),
        'hitung'    => route('ongkir.hitung'),
    ]) }};

    const form      = document.getElementById('form-checkout');
    const token     = form.querySelector('input[name="_token"]').value;
    const pProvinsi = document.getElementById('provinsi_id');
    const pKota     = document.getElementById('kota_id');
    const pKec      = document.getElementById('kecamatan_id');
    const kodePos   = document.getElementById('kode_pos');
    const daftar    = document.getElementById('daftar-layanan');
    const tOngkir   = document.getElementById('tampil-ongkir');
    const tTotal    = document.getElementById('tampil-total');
    const subtotal  = Number(tTotal.dataset.subtotal);

    const rupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

    async function ambil(url, opsi = {}) {
        const jawaban = await fetch(url, {
            ...opsi,
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
        });
        const isi = await jawaban.json();
        if (! jawaban.ok) throw new Error(isi.pesan || isi.message || 'Galat');
        return isi;
    }

    function isiPilihan(pilihan, data, teksAwal, terpilih) {
        pilihan.innerHTML = '';
        pilihan.add(new Option(teksAwal, ''));
        data.forEach(function (baris) {
            const opsi = new Option(baris.nama, baris.id);
            if (baris.kode_pos) opsi.dataset.kodePos = baris.kode_pos;
            if (String(baris.id) === String(terpilih)) opsi.selected = true;
            pilihan.add(opsi);
        });
        pilihan.disabled = data.length === 0;
    }

    function aturUlangLayanan(pesan) {
        daftar.textContent = pesan;
        tOngkir.textContent = '—';
        tTotal.textContent = rupiah(subtotal);
    }

    async function muatKota(terpilih) {
        isiPilihan(pKota, [], teks.kota);
        isiPilihan(pKec, [], teks.kecamatan);
        aturUlangLayanan(teks.alamat);
        if (! pProvinsi.value) return;

        pKota.innerHTML = '<option>' + teks.memuat + '</option>';
        try {
            isiPilihan(pKota, await ambil(alamat.kota + '/' + pProvinsi.value), teks.kota, terpilih);
        } catch (e) {
            isiPilihan(pKota, [], teks.kota);
            aturUlangLayanan(e.message);
        }
    }

    async function muatKecamatan(terpilih) {
        isiPilihan(pKec, [], teks.kecamatan);
        aturUlangLayanan(teks.alamat);
        if (! pKota.value) return;

        try {
            isiPilihan(pKec, await ambil(alamat.kecamatan + '/' + pKota.value), teks.kecamatan, terpilih);
        } catch (e) {
            aturUlangLayanan(e.message);
        }
    }

    async function muatLayanan(terpilih) {
        const opsi = pKec.selectedOptions[0];
        if (opsi && opsi.dataset.kodePos) kodePos.value = opsi.dataset.kodePos;
        if (! pKec.value) return aturUlangLayanan(teks.alamat);

        aturUlangLayanan(teks.memuat);
        try {
            const hasil = await ambil(alamat.hitung, {
                method: 'POST',
                body: new URLSearchParams({ kecamatan_id: pKec.value }),
            });
            tampilkanLayanan(hasil.layanan, terpilih);
        } catch (e) {
            aturUlangLayanan(e.message);
        }
    }

    function tampilkanLayanan(daftarLayanan, terpilih) {
        daftar.innerHTML = '';
        if (daftarLayanan.length === 0) return aturUlangLayanan(teks.kosong);

        daftarLayanan.forEach(function (l) {
            const label = document.createElement('label');
            label.className = 'flex items-center justify-between p-3 border border-gray-300 rounded mb-2 cursor-pointer';

            const kiri = document.createElement('span');
            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'layanan';
            radio.value = l.kode;
            radio.dataset.biaya = l.biaya;
            radio.className = 'mr-2';
            radio.checked = l.kode === terpilih;
            radio.addEventListener('change', perbaruiRingkasan);

            const nama = document.createElement('span');
            nama.textContent = l.kurir + ' ' + l.layanan
                + (l.estimasi ? ' · ' + teks.estimasi + ' ' + l.estimasi : '');

            const biaya = document.createElement('span');
            biaya.className = 'font-semibold';
            biaya.textContent = rupiah(l.biaya);

            kiri.append(radio, nama);
            label.append(kiri, biaya);
            daftar.append(label);
        });

        perbaruiRingkasan();
    }

    function perbaruiRingkasan() {
        const dipilih = daftar.querySelector('input[name="layanan"]:checked');
        if (! dipilih) return;
        const biaya = Number(dipilih.dataset.biaya);
        tOngkir.textContent = rupiah(biaya);
        tTotal.textContent = rupiah(subtotal + biaya);
    }

    pProvinsi.addEventListener('change', () => muatKota(null));
    pKota.addEventListener('change', () => muatKecamatan(null));
    pKec.addEventListener('change', () => muatLayanan(null));

    // Kembali dari validasi yang gagal: pulihkan pilihan lama secara berurutan
    (async function () {
        if (! pProvinsi.value) return;
        await muatKota(pKota.dataset.lama);
        if (! pKota.value) return;
        await muatKecamatan(pKec.dataset.lama);
        if (! pKec.value) return;
        await muatLayanan(daftar.dataset.lama);
    })();
});
</script>

@endsection