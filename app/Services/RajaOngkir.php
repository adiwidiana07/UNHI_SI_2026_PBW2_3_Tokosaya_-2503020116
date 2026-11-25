<?php

namespace App\Services;

use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RajaOngkir
{
    /** Daftar provinsi. Diambil dari API sekali saja, selebihnya dari basis data */
    public static function provinsi(): Collection
    {
        if (! Provinsi::exists()) {
            foreach (self::minta('get', 'destination/province') as $baris) {
                Provinsi::updateOrCreate(['id' => $baris['id']], ['nama' => $baris['name']]);
            }
        }

        return Provinsi::orderBy('nama')->get(['id', 'nama']);
    }

    /** Kota dalam satu provinsi. Diambil dari API saat provinsi itu pertama kali dipilih */
    public static function kota(int $provinsiId): Collection
    {
        if (! Kota::where('provinsi_id', $provinsiId)->exists()) {
            foreach (self::minta('get', "destination/city/{$provinsiId}") as $baris) {
                Kota::updateOrCreate(
                    ['id' => $baris['id']],
                    ['provinsi_id' => $provinsiId, 'nama' => $baris['name']]
                );
            }
        }
  return Kota::where('provinsi_id', $provinsiId)->orderBy('nama')->get(['id', 'nama']);
    }

    /** Kecamatan dalam satu kota, lengkap dengan kode pos */
    public static function kecamatan(int $kotaId): Collection
    {
        if (! Kecamatan::where('kota_id', $kotaId)->exists()) {
            foreach (self::minta('get', "destination/district/{$kotaId}") as $baris) {
                $kodePos = $baris['zip_code'] ?? null;

                Kecamatan::updateOrCreate(
                    ['id' => $baris['id']],
                    [
                        'kota_id'  => $kotaId,
                        'nama'     => $baris['name'],
                        // RajaOngkir menulis "0" bila kode posnya tidak diketahui
                        'kode_pos' => ($kodePos && $kodePos !== '0') ? $kodePos : null,
                    ]
                );
            }
        }

        return Kecamatan::where('kota_id', $kotaId)->orderBy('nama')->get(['id', 'nama', 'kode_pos']);
    }

    /**
     * Daftar layanan kurir beserta biayanya ke satu kecamatan tujuan.
     * Hasil disimpan sementara agar pertanyaan yang sama tidak memakan kuota lagi.
     */
    public static function hitung(int $tujuanId, int $beratGram): Collection
    {
        $asal  = config('rajaongkir.asal');
        $kurir = config('rajaongkir.kurir');

        if ($asal <= 0) {
            throw new RuntimeException('RAJAONGKIR_ASAL belum diisi di .env.');
        }

        $kunci = "ongkir:{$asal}:{$tujuanId}:{$beratGram}:{$kurir}";

        return Cache::remember($kunci, now()->addMinutes(config('rajaongkir.simpan_menit')),
            function () use ($asal, $tujuanId, $beratGram, $kurir) {

                $data = self::minta('post', 'calculate/district/domestic-cost', [
                    'origin'      => $asal,
                    'destination' => $tujuanId,
                    'weight'      => $beratGram,
                    'courier'     => $kurir,
                ]);

                return collect($data)
                    ->filter(fn ($baris) => (int) ($baris['cost'] ?? 0) > 0)
                    ->map(fn ($baris) => [
                        'kode'      => strtolower($baris['code']) . ':' . $baris['service'],
                        'kurir'     => strtoupper($baris['code']),
                        'layanan'   => $baris['service'],
                        'deskripsi' => $baris['description'] ?? '',
                        'biaya'     => (int) $baris['cost'],
                        'estimasi'  => (string) ($baris['etd'] ?? ''),
                    ])
                    ->sortBy('biaya')
                    ->values();
            });
    }

    /** Satu-satunya tempat yang benar-benar berbicara dengan RajaOngkir */
    private static function minta(string $metode, string $alamat, array $isi = []): array
    {
        $url = rtrim(config('rajaongkir.base_url'), '/') . '/' . $alamat;

        try {
            $http = Http::withHeaders(['key' => config('rajaongkir.api_key')])
                ->acceptJson()
                ->timeout(15);

            $jawaban = $metode === 'post'
                ? $http->asForm()->post($url, $isi)
                : $http->get($url, $isi);

        } catch (ConnectionException $e) {
            Log::warning('RajaOngkir tidak dapat dihubungi', [
                'alamat' => $alamat,
                'galat'  => $e->getMessage(),
            ]);

            throw new RuntimeException('RajaOngkir tidak dapat dihubungi.', 0, $e);
        }

        if ($jawaban->failed() || $jawaban->json('meta.status') !== 'success') {
            Log::warning('RajaOngkir menolak permintaan', [
                'alamat'  => $alamat,
                'status'  => $jawaban->status(),
                'jawaban' => $jawaban->json('meta'),
            ]);

            throw new RuntimeException(
                $jawaban->json('meta.message') ?? "RajaOngkir menjawab {$jawaban->status()}."
            );
        }

        return $jawaban->json('data') ?? [];
    }
}
