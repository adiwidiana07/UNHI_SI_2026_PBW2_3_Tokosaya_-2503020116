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
        $this->assertSame('batal', $pesanan->fresh()->status);

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

    public function test_expire_dari_order_id_lama_diabaikan(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);
        $pesanan->update(['midtrans_order_id' => 'INV-20261114-TEST-261114120000']);

        $this->kirim([
            'order_id' => 'INV-20261114-TEST',
            'status_code' => '407',
            'transaction_status' => 'expire',
        ])->assertOk()->assertJson(['pesan' => 'Diabaikan: order_id lama']);

        $this->assertSame('pending', $pesanan->fresh()->status_bayar);
        $this->assertSame(3, $produk->fresh()->stok);
    }

    public function test_deny_setelah_settlement_membalik_pembayaran(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim(['transaction_status' => 'settlement'])->assertOk();
        $this->kirim(['status_code' => '202', 'transaction_status' => 'deny'])
            ->assertOk()->assertJson(['status_bayar' => 'gagal']);

        $pesanan->refresh();

        $this->assertSame('gagal', $pesanan->status_bayar);
        $this->assertSame('batal', $pesanan->status);
        $this->assertNull($pesanan->dibayar_pada);
        $this->assertSame(5, $produk->fresh()->stok);
    }

    public function test_pelunasan_setelah_dibatalkan_admin_tidak_menghidupkan_pesanan(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);
        $pesanan->update(['status_bayar' => 'belum_bayar', 'status' => 'batal']);
        $pesanan->kembalikanStok();
        $pesanan->save();

        $this->kirim(['transaction_status' => 'settlement'])
            ->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $this->assertSame('batal', $pesanan->fresh()->status);
        $this->assertSame(5, $produk->fresh()->stok);
    }

    public function test_tombol_muat_ulang_status_membaca_status_dari_midtrans(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        \Illuminate\Support\Facades\Http::fake([
            '*/v2/INV-20261114-TEST/status' => \Illuminate\Support\Facades\Http::response([
                'order_id' => 'INV-20261114-TEST', 'status_code' => '200',
                'gross_amount' => '1000000.00', 'transaction_status' => 'settlement',
                'payment_type' => 'qris',
            ]),
        ]);

        $this->withSession(['pesanan_terakhir' => 'INV-20261114-TEST'])
            ->post(route('checkout.periksa', 'INV-20261114-TEST'))
            ->assertRedirect(route('checkout.selesai', 'INV-20261114-TEST'));

        $this->assertSame('lunas', $pesanan->fresh()->status_bayar);
        $this->assertSame('qris', $pesanan->fresh()->metode_bayar);
    }

    public function test_tombol_muat_ulang_status_aman_saat_midtrans_tak_terjangkau(): void
    {
        $pesanan = $this->pesanan($this->produk());

        \Illuminate\Support\Facades\Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException('putus');
        });

        $this->withSession(['pesanan_terakhir' => 'INV-20261114-TEST'])
            ->post(route('checkout.periksa', 'INV-20261114-TEST'))
            ->assertRedirect();

        $this->assertSame('pending', $pesanan->fresh()->status_bayar);
    }

    public function test_tombol_muat_ulang_status_ditolak_untuk_pesanan_orang_lain(): void
    {
        $this->pesanan($this->produk());

        $this->post(route('checkout.periksa', 'INV-20261114-TEST'))->assertForbidden();
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

    public function test_muatan_tidak_lengkap_ditolak(): void
    {
        $this->postJson(route('midtrans.notifikasi'), [
            'order_id' => 'INV-20261114-TEST',
            'transaction_status' => 'settlement',
        ])->assertStatus(400);

        $this->assertDatabaseCount('pembayarans', 0);
    }

    public function test_notifikasi_uji_dashboard_dengan_order_id_panjang_dicatat_utuh(): void
    {
        $orderId = 'payment_notif_test_M660784217_67f6ad4e-0ffe-4938-86db-180aa3f72b35';

        $this->kirim([
            'order_id' => $orderId,
            'transaction_status' => 'settlement',
            'transaction_id' => '67f6ad4e-0ffe-4938-86db-180aa3f72b35',
        ])->assertOk()->assertJson(['pesan' => 'Pesanan tidak ditemukan']);

        $this->assertDatabaseHas('pembayarans', [
            'order_id' => $orderId,
            'pesanan_id' => null,
            'signature_sah' => true,
        ]);
    }

    public function test_settlement_ganda_hanya_diterapkan_sekali(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim(['transaction_status' => 'settlement'])
            ->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $dibayarPada = $pesanan->fresh()->dibayar_pada;

        $this->travel(5)->minutes();

        $this->kirim(['transaction_status' => 'settlement'])
            ->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $pesanan->refresh();

        $this->assertSame('lunas', $pesanan->status_bayar);
        $this->assertTrue($pesanan->dibayar_pada->equalTo($dibayarPada));
        $this->assertSame(3, $produk->fresh()->stok);
        $this->assertFalse($pesanan->stok_dikembalikan);
        // Keduanya tetap tercatat sebagai jejak audit.
        $this->assertDatabaseCount('pembayarans', 2);
    }

    public function test_settlement_setelah_kedaluwarsa_memotong_stok_dan_memulihkan_pesanan(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim(['status_code' => '407', 'transaction_status' => 'expire'])->assertOk();
        $this->assertSame(5, $produk->fresh()->stok);

        $this->kirim(['transaction_status' => 'settlement'])
            ->assertOk()->assertJson(['status_bayar' => 'lunas']);

        $pesanan->refresh();

        $this->assertSame(3, $produk->fresh()->stok);
        $this->assertFalse($pesanan->stok_dikembalikan);
        $this->assertSame('baru', $pesanan->status);
    }

    public function test_pending_setelah_kedaluwarsa_tidak_menghidupkan_pesanan(): void
    {
        $produk = $this->produk(stok: 3);
        $pesanan = $this->pesanan($produk);

        $this->kirim([
            'status_code' => '407',
            'transaction_status' => 'expire',
        ])->assertOk()->assertJson(['status_bayar' => 'kedaluwarsa']);

        $this->kirim([
            'status_code' => '201',
            'transaction_status' => 'pending',
        ])->assertOk()->assertJson(['status_bayar' => 'kedaluwarsa']);

        $this->assertSame('kedaluwarsa', $pesanan->fresh()->status_bayar);
        $this->assertSame('batal', $pesanan->fresh()->status);
        $this->assertSame(5, $produk->fresh()->stok);
    }
}
