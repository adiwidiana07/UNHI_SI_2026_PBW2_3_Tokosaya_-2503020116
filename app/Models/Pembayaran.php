<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'pesanan_id', 'order_id', 'transaction_id', 'transaction_status',
        'fraud_status', 'payment_type', 'status_code', 'gross_amount',
        'signature_sah', 'muatan',
    ];

    protected $casts = [
        'muatan'        => 'array',
        'signature_sah' => 'boolean',
        'gross_amount'  => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}

