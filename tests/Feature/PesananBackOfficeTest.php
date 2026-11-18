<?php

namespace Tests\Feature;

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananBackOfficeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::firstOrCreate(['email' => 'admin@pesanan.test'], [
            'name' => 'Admin',
            'password' => 'admin123', 'role' => 'admin',
        ]);
    }

    private function pesanan(array $override = [], int $stok = 3): Pesanan
    {
        $kategori = Kategori::create(['nama_kategori' => 'Figure', 'slug' => 'figure']);
        $produk = Produk::create([
            'kategori_id' => $kategori->id, 'nama_produk' => 'Gundam', 'slug' => 'gundam',
            'harga' => 500000, 'stok' => $stok, 'berat' => 450, 'status' => 'aktif',
        ]);

        $pesanan = Pesanan::create(array_merge([
            'kode_pesanan' => 'INV-20261115-BO01', 'nama_pemesan' => 'Budi',
            'email' => 'budi@example.com', 'telepon' => '081234567890',
            'alamat' => 'Jalan Raya No. 1', 'kota' => 'Denpasar',
            'subtotal' => 1000000, 'ongkir' => 0, 'total' => 1000000,
            'status' => 'baru', 'status_bayar' => 'belum_bayar',
        ], $override));

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id, 'produk_id' => $produk->id,
            'nama_produk' => 'Gundam', 'harga' => 500000, 'jumlah' => 2, 'subtotal' => 1000000,
        ]);

        return $pesanan;
    }

    private function ubah(Pesanan $pesanan, string $status)
    {
        return $this->actingAs($this->admin())
            ->from(route('back-office.pesanan.show', $pesanan))
            ->patch(route('back-office.pesanan.status', $pesanan), ['status' => $status]);
    }

    public function test_pesanan_belum_lunas_tidak_bisa_ditandai_dikirim(): void
    {
        $pesanan = $this->pesanan();

        $this->ubah($pesanan, 'dikirim')->assertSessionHas('gagal');

        $this->assertSame('baru', $pesanan->fresh()->status);
    }

    public function test_pesanan_lunas_bisa_ditandai_dikirim(): void
    {
        $pesanan = $this->pesanan(['status_bayar' => 'lunas']);

        $this->ubah($pesanan, 'dikirim')->assertSessionHas('sukses');

        $this->assertSame('dikirim', $pesanan->fresh()->status);
    }

    public function test_admin_membatalkan_pesanan_belum_bayar_mengembalikan_stok_sekali(): void
    {
        $pesanan = $this->pesanan(stok: 3);
        $produk = Produk::first();

        $this->ubah($pesanan, 'batal')->assertSessionHas('sukses');
        $this->ubah($pesanan, 'batal');

        $this->assertSame('batal', $pesanan->fresh()->status);
        $this->assertTrue($pesanan->fresh()->stok_dikembalikan);
        $this->assertSame(5, $produk->fresh()->stok);
    }

    public function test_pesanan_pending_tidak_bisa_dibatalkan_admin(): void
    {
        $pesanan = $this->pesanan(['status_bayar' => 'pending'], stok: 3);

        $this->ubah($pesanan, 'batal')->assertSessionHas('gagal');

        $this->assertSame('baru', $pesanan->fresh()->status);
        $this->assertSame(3, Produk::first()->stok);
    }

    public function test_pesanan_batal_tidak_bisa_diaktifkan_lagi(): void
    {
        $pesanan = $this->pesanan(['status' => 'batal']);

        $this->ubah($pesanan, 'baru')->assertSessionHas('gagal');

        $this->assertSame('batal', $pesanan->fresh()->status);
    }

    public function test_daftar_dan_rincian_menampilkan_pembayaran(): void
    {
        $pesanan = $this->pesanan([
            'status_bayar' => 'lunas', 'metode_bayar' => 'bank_transfer',
            'midtrans_order_id' => 'INV-20261115-BO01',
        ]);

        Pembayaran::create([
            'pesanan_id' => $pesanan->id, 'order_id' => 'INV-20261115-BO01',
            'transaction_status' => 'settlement', 'status_code' => '200',
            'gross_amount' => 1000000, 'signature_sah' => false, 'muatan' => [],
        ]);

        $admin = $this->admin();

        $this->actingAs($admin)->get(route('back-office.pesanan.index'))
            ->assertOk()->assertSee('Pembayaran')->assertSee('bank_transfer');

        $this->actingAs($admin)->get(route('back-office.pesanan.show', $pesanan))
            ->assertOk()
            ->assertSee('Riwayat Notifikasi Midtrans')
            ->assertSee('Tidak Sah')
            ->assertSee('INV-20261115-BO01');
    }
}
