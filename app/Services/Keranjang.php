<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Support\Collection;

/**
 * Mengelola isi keranjang belanja di dalam session.
 *
 * Struktur data yang disimpan:
 *     [ produk_id => jumlah, produk_id => jumlah, ... ]
 */
class Keranjang
{
    /** Nama kunci di dalam session */
    public const KUNCI = 'keranjang';

    /** Mengambil isi mentah keranjang */
    public static function isi(): array
    {
        return session(self::KUNCI, []);
    }

    /** Menyimpan isi keranjang kembali ke session */
    private static function simpan(array $isi): void
    {
        session([self::KUNCI => $isi]);
    }

    /** Menambahkan produk ke keranjang */
    public static function tambah(int $produkId, int $jumlah = 1): void
    {
        $isi = self::isi();

        // Kalau produknya sudah ada, jumlahnya ditambahkan
        $isi[$produkId] = ($isi[$produkId] ?? 0) + $jumlah;

        self::simpan($isi);
    }

    /** Mengubah jumlah sebuah produk */
    public static function ubah(int $produkId, int $jumlah): void
    {
        $isi = self::isi();

        if ($jumlah < 1) {
            unset($isi[$produkId]);      // jumlah 0 berarti dihapus
        } else {
            $isi[$produkId] = $jumlah;
        }

        self::simpan($isi);
    }

    /** Menghapus satu produk dari keranjang */
    public static function hapus(int $produkId): void
    {
        $isi = self::isi();

        unset($isi[$produkId]);

        self::simpan($isi);
    }

    /** Mengosongkan seluruh keranjang */
    public static function kosongkan(): void
    {
        session()->forget(self::KUNCI);
    }

    /** Menghitung total jumlah barang (bukan jenis produk) */
    public static function totalItem(): int
    {
        return array_sum(self::isi());
    }

    /**
     * Mengambil daftar item lengkap dengan data produknya.
     * Data produk selalu diambil terbaru dari database.
     */
    public static function daftarItem(): Collection
    {
        $isi = self::isi();

        if (empty($isi)) {
            return collect();
        }

        // Satu query untuk semua produk di keranjang
        $produks = Produk::with('kategori')
                         ->whereIn('id', array_keys($isi))
                         ->where('status', 'aktif')
                         ->get();

        // Bersihkan produk yang sudah dihapus atau dinonaktifkan admin
        $idTersedia = $produks->pluck('id')->all();
        $isiBersih  = array_intersect_key($isi, array_flip($idTersedia));

        if (count($isiBersih) !== count($isi)) {
            self::simpan($isiBersih);
        }

        return $produks->map(function (Produk $produk) use ($isiBersih) {
            $jumlah = $isiBersih[$produk->id];

            return (object) [
                'produk'   => $produk,
                'jumlah'   => $jumlah,
                'subtotal' => $produk->harga * $jumlah,
            ];
        });
    }

    /** Menghitung total harga seluruh keranjang */
    public static function totalHarga(): float
    {
        return self::daftarItem()->sum('subtotal');
    }
}