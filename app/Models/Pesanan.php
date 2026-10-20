<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'kode_pesanan', 'nama_pemesan', 'email', 'telepon',
        'alamat', 'kota', 'kode_pos', 'catatan',
        'subtotal', 'ongkir', 'total', 'status',
    ];

    /** Satu pesanan punya banyak baris rincian */
    public function detail()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function totalRupiah(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /** Warna lencana status untuk tampilan back office */
    public function warnaStatus(): string
    {
        return match ($this->status) {
            'baru'     => 'text-bg-warning',
            'diproses' => 'text-bg-primary',
            'dikirim'  => 'text-bg-info',
            'selesai'  => 'text-bg-success',
            'batal'    => 'text-bg-secondary',
        };
    }
}
