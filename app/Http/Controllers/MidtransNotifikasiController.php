<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class MidtransNotifikasiController extends Controller
{
    public function __invoke(Request $request)
    {
        $muatan = $request->all();

        // 1. Validasi muatan wajib
        foreach ([
            'order_id',
            'status_code',
            'gross_amount',
            'transaction_status',
            'signature_key',
        ] as $wajib) {
            if (! isset($muatan[$wajib])) {
                return response()->json([
                    'pesan' => 'Muatan tidak lengkap',
                ], 400);
            }
        }

        $kunci = (string) config('midtrans.signature_key');

        if ($kunci === '') {
            Log::error('MIDTRANS_SERVER_KEY belum diatur.');

            return response()->json([
                'pesan' => 'Konfigurasi pembayaran belum lengkap',
            ], 500);
        }

        // 2. Verifikasi signature
        $hitungan = hash(
            'sha512',
            $muatan['order_id'] .
            $muatan['status_code'] .
            $muatan['gross_amount'] .
            $kunci
        );

        $sah = hash_equals(
            $hitungan,
            (string) $muatan['signature_key']
        );

        // 3. Cari pesanan
        $pesanan = Pesanan::where(
            'midtrans_order_id',
            $muatan['order_id']
        )->first();

        // Notifikasi bisa datang untuk order_id lama setelah pembeli
        // mencoba membayar ulang (order_id diberi akhiran waktu).
        if (! $pesanan) {
            $kodePesanan = preg_replace('/-\d{12}$/', '', (string) $muatan['order_id']);

            $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->first();
        }

        // 4. Simpan seluruh notifikasi
        Pembayaran::create([
            'pesanan_id' => $pesanan?->id,
            'order_id' => $muatan['order_id'],
            'transaction_id' => $muatan['transaction_id'] ?? null,
            'transaction_status' => $muatan['transaction_status'],
            'fraud_status' => $muatan['fraud_status'] ?? null,
            'payment_type' => $muatan['payment_type'] ?? null,
            'status_code' => $muatan['status_code'],
            'gross_amount' => $muatan['gross_amount'],
            'signature_sah' => $sah,
            'muatan' => $muatan,
        ]);

        // 5. Tolak signature palsu
        if (! $sah) {
            return response()->json([
                'pesan' => 'Tanda tangan tidak sah',
            ], 403);
        }

        if (! $pesanan) {
            return response()->json([
                'pesan' => 'Pesanan tidak ditemukan',
            ], 200);
        }

        // 6. Terapkan status pembayaran
        try {
            $hasil = Midtrans::terapkan($pesanan, $muatan);
        } catch (Throwable $e) {
            Log::error('Notifikasi Midtrans gagal diterapkan', [
                'order_id' => $muatan['order_id'],
                'galat' => $e->getMessage(),
            ]);

            return response()->json([
                'pesan' => 'Notifikasi gagal diproses',
            ], 500);
        }

        return response()->json([
            'pesan' => 'Diterima',
            'status_bayar' => $hasil,
        ], 200);
    }
}
{
    
}