<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    private function kategori(string $nama = 'Figure'): Kategori
    {
        return Kategori::firstOrCreate(
            ['slug' => \Illuminate\Support\Str::slug($nama)],
            ['nama_kategori' => $nama],
        );
    }

    private function produk(array $override = []): Produk
    {
        return Produk::create(array_merge([
            'kategori_id' => $this->kategori()->id,
            'nama_produk' => 'Gundam RX-78-2',
            'slug'        => 'gundam-rx-78-2-a3k9x',
            'kode_produk' => 'RX78',
            'harga'       => 899900,
            'harga_coret' => 1099900,
            'stok'        => 12,
            'berat'       => 450,
            'status'      => 'aktif',
        ], $override));
    }

    public function test_beranda_menampilkan_maksimal_empat_produk_aktif(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->produk([
                'nama_produk' => 'Produk ' . $i,
                'slug'        => 'produk-' . $i,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewIs('user_front.home');
        $this->assertCount(4, $response->viewData('produkPopuler'));
    }

    public function test_beranda_tidak_menampilkan_produk_nonaktif(): void
    {
        $this->produk([
            'nama_produk' => 'Produk Disembunyikan',
            'slug'        => 'produk-nonaktif',
            'status'      => 'nonaktif',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Produk Disembunyikan');
        $this->assertCount(0, $response->viewData('produkPopuler'));
    }

    public function test_beranda_menampilkan_nama_dan_harga(): void
    {
        $this->produk();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Gundam RX-78-2');
        $response->assertSee('Rp 899.900');
        $response->assertSee('Figure');
    }

    public function test_halaman_kontak_tampil(): void
    {
        $this->get(route('kontak'))
            ->assertOk()
            ->assertViewIs('user_front.kontak');
    }

    public function test_daftar_produk_hanya_menampilkan_yang_aktif(): void
    {
        $this->produk(['nama_produk' => 'Produk Aktif', 'slug' => 'produk-aktif']);
        $this->produk([
            'nama_produk' => 'Produk Nonaktif',
            'slug'        => 'produk-nonaktif',
            'status'      => 'nonaktif',
        ]);

        $response = $this->get(route('produk.index'));

        $response->assertOk();
        $response->assertViewIs('user_front.produk.index');
        $response->assertSee('Produk Aktif');
        $response->assertDontSee('Produk Nonaktif');
    }

    public function test_daftar_produk_mengirim_kategori_untuk_filter(): void
    {
        $this->kategori('Figure');
        $this->kategori('Gunpla');

        $response = $this->get(route('produk.index'));

        $response->assertOk();
        $this->assertCount(2, $response->viewData('daftarKategori'));
        $response->assertSee('Gunpla');
    }

    public function test_halaman_produk_dibuka_lewat_slug(): void
    {
        $produk = $this->produk();

        $response = $this->get(route('produk.show', $produk));

        $response->assertOk();
        $response->assertViewIs('user_front.produk.show');
        $response->assertViewHas('produk', fn ($p) => $p->is($produk));
        $response->assertSee('Gundam RX-78-2');
        $response->assertSee('Rp 899.900');
    }

    public function test_url_produk_memakai_slug_bukan_id(): void
    {
        $produk = $this->produk();

        $url = route('produk.show', $produk);

        $this->assertStringContainsString('gundam-rx-78-2-a3k9x', $url);
        $this->assertStringNotContainsString('/'.$produk->id, $url);
    }

    public function test_produk_nonaktif_tidak_bisa_dibuka_pembeli(): void
    {
        $produk = $this->produk(['status' => 'nonaktif']);

        $this->get(route('produk.show', $produk))->assertNotFound();
    }

    public function test_slug_tidak_ada_menghasilkan_404(): void
    {
        $this->get('/produk/tidak-ada-xyz')->assertNotFound();
    }

    public function test_produk_lama_yang_pakai_id_tidak_lagi_valid(): void
    {
        $produk = $this->produk();

        // URL gaya lama /produk/{id} tidak lagi mencocokkan slug
        $this->get('/produk/'.$produk->id)->assertNotFound();
    }
}