<?php

return [

    'api_key'  => env('RAJAONGKIR_API_KEY'),

    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),

    // id kecamatan gudang atau toko — lihat Pertemuan 11 §H.4
    'asal'     => (int) env('RAJAONGKIR_ASAL', 0),

    // kode kurir dipisahkan titik dua, mis. jne:pos:tiki
    'kurir'    => env('RAJAONGKIR_KURIR', 'jne:pos:tiki'),

    // berat pengganti per barang bila berat produk belum diisi (gram)
    'berat_bawaan' => 1000,

    // lama hasil hitungan ongkir disimpan sementara (menit)
    'simpan_menit' => 360,

];
