<?php

namespace Tests\Feature;

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransNotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['midtrans.signature_key' => 'SB-Mid-server-uji']);
    }

    private function produk(int $stok = 3): Produk
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Figure',
            'slug' => 'figure',
        ]);

        return Produk::create([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Gundam RX-78-2',
            'slug' => 'gundam-rx-78-2',
            'kode_produk' => 'RX78',
            'harga' => 500000,
            'stok' => $stok,
            'berat' => 450,
            'status' => 'aktif',
        ]);
    }

    private function pesanan(Produk $produk, int $jumlah = 2): Pesanan
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'INV-20261114-TEST',
            'nama_pemesan' => 'Budi',
            'email' => 'budi@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jalan Raya No. 1',
            'kota' => 'Denpasar',
            'subtotal' => $produk->harga * $jumlah,
            'ongkir' => 0,
            'total' => $produk->harga * $jumlah,
            'status' => 'baru',
            'status_bayar' => 'pending',
            'midtrans_order_id' => 'INV-20261114-TEST',
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'harga' => $produk->harga,
            'jumlah' => $jumlah,
            'subtotal' => $produk->harga * $jumlah,
        ]);

        return $pesanan;
    }

    private function kirim(array $muatan, ?string $signature = null)
    {
        $muatan += [
            'order_id' => 'INV-20261114-TEST',
            'status_code' => '200',
            'gross_amount' => '1000000.00',
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
        ];

        $muatan['signature_key'] = $signature ?? hash(
            'sha512',
            $muatan['order_id'] .
            $muatan['status_code'] .
            $muatan['gross_amount'] .
            config('midtrans.signature_key')
        );

        return $this->postJson(route('midtrans.notifikasi'), $muatan);
    }

    public function test_signature_palsu_ditolak_dan_tetap_dicatat(): void
    {
        $pesanan = $this->pesanan($this->produk());

        $response = $this->kirim([], 'palsu');

        $response->assertForbidden();
        $this->assertSame('pending', $pesanan->fresh()->status_bayar);
        $this->assertDatabaseHas('pembayarans', [
            'order_id' => 'INV-20261114-TEST',
            'signature_sah' => false,
        ]);
    }

    public function test_settlement_menandai_lunas_tanpa_mengubah_stok(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $response = $this->kirim([
            'transaction_status' => 'settlement',
            'transaction_id' => 'trx-123',
        ]);

        $response->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $pesanan->refresh();

        $this->assertSame('lunas', $pesanan->status_bayar);
        $this->assertSame('bank_transfer', $pesanan->metode_bayar);
        $this->assertNotNull($pesanan->dibayar_pada);
        $this->assertSame(3, $produk->fresh()->stok);
        $this->assertDatabaseHas('pembayarans', [
            'order_id' => 'INV-20261114-TEST',
            'transaction_id' => 'trx-123',
            'signature_sah' => true,
        ]);
    }

    public function test_expire_mengembalikan_stok_hanya_sekali(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim([
            'status_code' => '407',
            'transaction_status' => 'expire',
        ])->assertOk()->assertJson(['status_bayar' => 'kedaluwarsa']);

        $this->assertSame(5, $produk->fresh()->stok);
        $this->assertTrue($pesanan->fresh()->stok_dikembalikan);

        // Notifikasi yang sama datang lagi tidak boleh menambah stok lagi.
        $this->kirim([
            'status_code' => '407',
            'transaction_status' => 'expire',
        ])->assertOk();

        $this->assertSame(5, $produk->fresh()->stok);
    }

    public function test_notifikasi_order_id_lama_tetap_menemukan_pesanan(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        // Pembeli pernah menekan "bayar lagi", sehingga order_id tersimpan
        // sudah memakai akhiran waktu.
        $pesanan->update([
            'midtrans_order_id' => 'INV-20261114-TEST-261114120000',
        ]);

        $this->kirim([
            'order_id' => 'INV-20261114-TEST',
            'transaction_status' => 'settlement',
        ])->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $this->assertSame('lunas', $pesanan->fresh()->status_bayar);
    }

    public function test_notifikasi_lama_tidak_menurunkan_status_lunas(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim(['transaction_status' => 'settlement'])->assertOk();
        $this->kirim([
            'status_code' => '201',
            'transaction_status' => 'pending',
        ])->assertOk();

        $this->assertSame('lunas', $pesanan->fresh()->status_bayar);
    }
}
