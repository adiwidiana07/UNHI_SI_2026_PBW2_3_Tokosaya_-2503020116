<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    /**
     * Satu kategori punya BANYAK produk.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}