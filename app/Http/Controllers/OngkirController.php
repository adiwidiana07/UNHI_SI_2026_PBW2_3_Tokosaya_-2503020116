<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Provinsi;
use App\Services\Keranjang;
use App\Services\RajaOngkir;
use Illuminate\Http\Request;
use RuntimeException;

class OngkirController extends Controller
{
    /** Daftar kota dalam satu provinsi */
    public function kota(int $provinsi)
    {
        abort_unless(Provinsi::whereKey($provinsi)->exists(), 404);

        try {
            return response()->json(RajaOngkir::kota($provinsi));
        } catch (RuntimeException $e) {
            return $this->gagal();
        }
    }

    /** Daftar kecamatan dalam satu kota */
    public function kecamatan(int $kota)
    {
        abort_unless(Kota::whereKey($kota)->exists(), 404);

        try {
            return response()->json(RajaOngkir::kecamatan($kota));
        } catch (RuntimeException $e) {
            return $this->gagal();
        }
    }

    /** Pilihan layanan kurir untuk isi keranjang saat ini */
    public function hitung(Request $request)
    {
        $data = $request->validate([
            'kecamatan_id' => ['required', 'integer', 'exists:kecamatans,id'],
        ]);

        $berat = Keranjang::totalBerat();

        if ($berat === 0) {
            return response()->json(['pesan' => __('web.keranjang_kosong')], 422);
        }

        try {
            $layanan = RajaOngkir::hitung($data['kecamatan_id'], $berat);
        } catch (RuntimeException $e) {
            return $this->gagal();
        }

        return response()->json([
            'berat'   => $berat,
            'layanan' => $layanan,
        ]);
    }

    /** Jawaban seragam saat RajaOngkir tidak bisa dipakai */
    private function gagal()
    {
        return response()->json(['pesan' => __('web.ongkir_gagal')], 503);
    }
}
