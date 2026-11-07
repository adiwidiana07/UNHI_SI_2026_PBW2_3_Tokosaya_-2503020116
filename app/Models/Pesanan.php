<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'kode_pesanan', 'nama_pemesan', 'email', 'telepon',
        'alamat', 'kota', 'kode_pos', 'catatan',
        'subtotal', 'ongkir', 'total', 'status',
        // ---- baru di Pertemuan 9 ----
        'status_bayar', 'midtrans_order_id', 'midtrans_redirect_url',
        'batas_bayar', 'metode_bayar', 'dibayar_pada', 'stok_dikembalikan',
    ];

    protected $casts = [
        'batas_bayar'       => 'datetime',
        'dibayar_pada'      => 'datetime',
        'stok_dikembalikan' => 'boolean',
    ];

    /** Satu pesanan punya banyak baris rincian */
    public function detail()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /** Riwayat notifikasi pembayaran pesanan ini */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class)->latest();
    }

    public function totalRupiah(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /** Benar bila uangnya sudah masuk */
    public function sudahLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    /** Benar bila pesanan ini masih boleh dibayar */
    public function bisaDibayar(): bool
    {
        return in_array($this->status_bayar, ['belum_bayar', 'pending'], true)
            && $this->status !== 'batal';
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
            default    => 'text-bg-secondary',
        };
    }

    /** Label (dua bahasa, kuncinya ditambahkan di J.1) dan jenis warnanya */
    public function labelBayar(): array
    {
        return match ($this->status_bayar) {
            'lunas'       => [__('web.lunas'),         'success'],
            'pending'     => [__('web.menunggu'),      'warning'],
            'gagal'       => [__('web.gagal'),         'danger'],
            'kedaluwarsa' => [__('web.kedaluwarsa'),   'secondary'],
            default       => [__('web.belum_dibayar'), 'secondary'],
        };
    }
}


