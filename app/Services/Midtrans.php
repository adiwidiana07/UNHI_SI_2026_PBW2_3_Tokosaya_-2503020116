<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;           // dipakai mulai Pertemuan 10
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class Midtrans
{
    /** Lama waktu pembeli boleh membayar, dalam jam */
    const BATAS_JAM = 24;

    /**
     * Meminta halaman pembayaran kepada Midtrans.
     * Mengembalikan alamat yang harus dituju pembeli.
     *
     * @throws RuntimeException bila halaman pembayaran tidak dapat dibuat
     */
    public static function mintaHalamanBayar(Pesanan $pesanan): string
    {
        // ---------- 1. Pesanan yang tidak boleh dibayar lagi ----------
        if (! $pesanan->bisaDibayar()) {
            throw new RuntimeException("Pesanan {$pesanan->kode_pesanan} sudah tidak dapat dibayar.");
        }

        // ---------- 2. Halaman yang masih berlaku dipakai lagi ----------
        // Pembeli yang sudah memilih VA perlu melihat nomornya lagi,
        // dan Midtrans menolak halaman baru untuk order_id itu — lihat H.4
        if ($pesanan->status_bayar === 'pending'
            && $pesanan->midtrans_redirect_url
            && $pesanan->batas_bayar?->isFuture()) {
            return $pesanan->midtrans_redirect_url;
        }

        // ---------- 3. Menentukan order_id ----------
        // Pertama kali sama dengan kode_pesanan. Sesudahnya order_id lama
        // sudah terpakai di Midtrans, jadi diberi akhiran waktu
        $orderId = $pesanan->midtrans_order_id
            ? $pesanan->kode_pesanan . '-' . now()->format('ymdHis')
            : $pesanan->kode_pesanan;

        // ---------- 4. Menyusun rincian barang ----------
        $pesanan->loadMissing('detail');

        $rincian = [];

        foreach ($pesanan->detail as $baris) {
            $rincian[] = [
                'id'       => (string) ($baris->produk_id ?? 'X'),
                'name'     => Str::limit($baris->nama_produk, 47, '...'),
                'price'    => (int) round($baris->harga),
                'quantity' => (int) $baris->jumlah,
            ];
        }

        // Ongkos kirim ikut sebagai satu baris agar jumlahnya pas.
        // Masih 0 sampai Pertemuan 11 (RajaOngkir)
        if ((int) round($pesanan->ongkir) > 0) {
            $rincian[] = [
                'id'       => 'ONGKIR',
                'name'     => 'Ongkos Kirim',
                'price'    => (int) round($pesanan->ongkir),
                'quantity' => 1,
            ];
        }

        // ---------- 5. Memeriksa jumlah sebelum dikirim ----------
        $total         = (int) round($pesanan->total);
        $jumlahRincian = collect($rincian)->sum(fn ($r) => $r['price'] * $r['quantity']);

        if ($jumlahRincian !== $total) {
            throw new RuntimeException(
                "Jumlah rincian ({$jumlahRincian}) tidak sama dengan total pesanan ({$total})."
            );
        }

        // ---------- 6. Muatan JSON ----------
        $mulai = now();

        $muatan = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => $rincian,
            'customer_details' => [
                'first_name' => $pesanan->nama_pemesan,
                'email'      => $pesanan->email,
                'phone'      => $pesanan->telepon,
                'shipping_address' => [
                    'first_name'   => $pesanan->nama_pemesan,
                    'phone'        => $pesanan->telepon,
                    'address'      => $pesanan->alamat,
                    'city'         => $pesanan->kota,
                    'postal_code'  => $pesanan->kode_pos,
                    'country_code' => 'IDN',
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.selesai', $pesanan->kode_pesanan),
            ],
            'expiry' => [
                'start_time' => $mulai->format('Y-m-d H:i:s O'),
                'unit'       => 'hours',
                'duration'   => self::BATAS_JAM,
            ],
        ];

        // ---------- 7. Mengirim ----------
        try {
            $jawaban = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->acceptJson()
                ->timeout(15)
                ->post(config('midtrans.snap_url'), $muatan);
        } catch (ConnectionException $e) {
            // Internet putus, nama domain tidak ditemukan,
            // atau Midtrans tidak menjawab dalam 15 detik
            Log::error('Midtrans tidak dapat dihubungi', [
                'kode_pesanan' => $pesanan->kode_pesanan,
                'galat'        => $e->getMessage(),
            ]);

            throw new RuntimeException('Midtrans tidak dapat dihubungi.', 0, $e);
        }

        if ($jawaban->failed() || ! $jawaban->json('redirect_url')) {
            Log::error('Midtrans menolak permintaan', [
                'kode_pesanan' => $pesanan->kode_pesanan,
                'status'       => $jawaban->status(),
                'jawaban'      => $jawaban->json(),
            ]);

            throw new RuntimeException(
                $jawaban->json('error_messages.0') ?? "Midtrans menjawab {$jawaban->status()}."
            );
        }

        // ---------- 8. Menyimpan jejaknya ----------
        $pesanan->update([
            'midtrans_order_id'     => $orderId,
            'midtrans_redirect_url' => $jawaban->json('redirect_url'),
            'batas_bayar'           => $mulai->copy()->addHours(self::BATAS_JAM),
            'status_bayar'          => 'pending',
        ]);

        return $pesanan->midtrans_redirect_url;
    }

    /**
     * Menerapkan notifikasi pembayaran Midtrans ke pesanan.
     *
     * Hanya boleh dipanggil setelah tanda tangan notifikasi terbukti sah.
     * Mengembalikan status_bayar pesanan setelah perubahan.
     */
    public static function terapkan(Pesanan $pesanan, array $muatan): string
    {
        $statusTransaksi = (string) ($muatan['transaction_status'] ?? '');
        $statusFraud     = $muatan['fraud_status'] ?? null;

        $statusBaru = self::petakanStatus($statusTransaksi, $statusFraud);

        if ($statusBaru === null) {
            Log::warning('Status transaksi Midtrans tidak dikenal', [
                'kode_pesanan'       => $pesanan->kode_pesanan,
                'transaction_status' => $statusTransaksi,
            ]);

            return $pesanan->status_bayar;
        }

        // Tanda tangan sudah sah, tetapi nominal tetap diperiksa agar
        // pesanan tidak tertandai lunas dengan jumlah yang salah.
        $nominal = (int) round((float) ($muatan['gross_amount'] ?? 0));
        $total   = (int) round($pesanan->total);

        if ($nominal !== $total) {
            Log::critical('Nominal notifikasi Midtrans tidak sama dengan total pesanan', [
                'kode_pesanan' => $pesanan->kode_pesanan,
                'nominal'      => $nominal,
                'total'        => $total,
            ]);

            return $pesanan->status_bayar;
        }

        return DB::transaction(function () use ($pesanan, $muatan, $statusTransaksi, $statusBaru) {

            // Kunci baris agar dua notifikasi yang datang bersamaan
            // tidak mengembalikan stok dua kali.
            $terkini = Pesanan::lockForUpdate()->find($pesanan->getKey());

            if (! $terkini) {
                return $pesanan->status_bayar;
            }

            // Refund/chargeback memang boleh membalik status lunas.
            $pembalikanDana = in_array($statusTransaksi, [
                'refund', 'partial_refund', 'chargeback', 'partial_chargeback',
            ], true);

            // Notifikasi lama tidak boleh menurunkan status yang sudah lunas.
            if ($terkini->status_bayar === 'lunas'
                && $statusBaru !== 'lunas'
                && ! $pembalikanDana) {
                return $terkini->status_bayar;
            }

            // Stok kembali hanya untuk transaksi yang benar-benar batal,
            // bukan untuk refund (barangnya mungkin sudah dikirim).
            if (! $pembalikanDana
                && in_array($statusBaru, ['gagal', 'kedaluwarsa'], true)
                && ! $terkini->stok_dikembalikan) {
                self::kembalikanStok($terkini);
                $terkini->stok_dikembalikan = true;
            }

            // Pembayaran sah yang masuk setelah stok sempat dikembalikan
            // harus memotong stok lagi supaya jumlahnya tetap benar.
            if ($statusBaru === 'lunas' && $terkini->stok_dikembalikan) {
                self::potongStok($terkini);
                $terkini->stok_dikembalikan = false;
            }

            $terkini->status_bayar = $statusBaru;

            if (! empty($muatan['payment_type'])) {
                $terkini->metode_bayar = $muatan['payment_type'];
            }

            if ($statusBaru === 'lunas' && ! $terkini->dibayar_pada) {
                $terkini->dibayar_pada = now();
            }

            $terkini->save();

            return $terkini->status_bayar;
        });
    }

    /** Menerjemahkan status Midtrans menjadi status_bayar milik aplikasi. */
    private static function petakanStatus(string $transaksi, ?string $fraud): ?string
    {
        return match ($transaksi) {
            'capture' => match ($fraud) {
                'challenge' => 'pending',
                'deny'      => 'gagal',
                default     => 'lunas',
            },
            'settlement' => 'lunas',
            'pending', 'authorize' => 'pending',
            'deny', 'cancel', 'failure' => 'gagal',
            'refund', 'partial_refund', 'chargeback', 'partial_chargeback' => 'gagal',
            'expire' => 'kedaluwarsa',
            default => null,
        };
    }

    /** Mengembalikan stok seluruh baris pesanan. */
    private static function kembalikanStok(Pesanan $pesanan): void
    {
        $pesanan->loadMissing('detail');

        foreach ($pesanan->detail as $baris) {
            if ($baris->produk_id) {
                Produk::whereKey($baris->produk_id)->increment('stok', $baris->jumlah);
            }
        }
    }

    /** Memotong lagi stok pesanan yang sempat dikembalikan. */
    private static function potongStok(Pesanan $pesanan): void
    {
        $pesanan->loadMissing('detail');

        foreach ($pesanan->detail as $baris) {
            if ($baris->produk_id) {
                Produk::whereKey($baris->produk_id)->decrement('stok', $baris->jumlah);
            }
        }
    }
}
