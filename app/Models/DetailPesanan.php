<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $fillable = [
        'pesanan_id', 'produk_id', 'nama_produk',
        'harga', 'jumlah', 'subtotal',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /** Bisa bernilai null bila produknya sudah dihapus */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function hargaRupiah(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function subtotalRupiah(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}