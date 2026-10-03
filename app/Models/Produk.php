<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'slug',
        'kode_produk',
        'deskripsi',
        'harga',
        'harga_coret',
        'stok',
        'berat',
        'gambar',
        'status',
    ];

    /**
     * Satu produk MILIK satu kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Menampilkan harga dalam format rupiah.
     * Ditulis sekali di sini, dipakai di semua halaman.
     */
    public function hargaRupiah(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function hargaCoretRupiah(): ?string
    {
        if (! $this->harga_coret) {
            return null;
        }

        return 'Rp ' . number_format($this->harga_coret, 0, ',', '.');
    }
}