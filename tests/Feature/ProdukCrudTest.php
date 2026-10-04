<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdukCrudTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create([
            'name'     => 'Admin Wibu Store',
            'email'    => 'admin@wproduk.test',
            'password' => 'admin123',
            'role'     => 'admin',
        ]);
    }

    private function kategori(): Kategori
    {
        return Kategori::firstOrCreate(['slug' => 'figure'], ['nama_kategori' => 'Figure']);
    }

    private function isian(array $override = []): array
    {
        return array_merge([
            'kategori_id' => $this->kategori()->id,
            'nama_produk' => 'Gundam RX-78-2',
            'slug'        => 'gundam-rx-78-2',
            'kode_produk' => 'RX78',
            'harga'       => 899900,
            'stok'        => 12,
            'berat'       => 450,
            'status'      => 'aktif',
        ], $override);
    }

    private function produkDb(): array
    {
        return Produk::query()
            ->where('nama_produk', 'Gundam RX-78-2')
            ->firstOrFail()
            ->only(['gambar']);
    }

    public function test_halaman_daftar_produk_tampil(): void
    {
        Produk::create($this->isian());

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.produk.index'));

        $response->assertOk();
        $response->assertViewIs('back_office.produk.index');
        $response->assertSee('Gundam RX-78-2');
        $response->assertSee('Figure');
    }

    public function test_halaman_tambah_produk_menampilkan_kategori(): void
    {
        $this->kategori();

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.produk.create'));

        $response->assertOk();
        $response->assertViewIs('back_office.produk.create');
        $response->assertSee('Figure');
    }

    public function test_admin_bisa_menambah_produk(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->adminUser())
            ->post(route('back-office.produk.store'), $this->isian([
                'gambar' => UploadedFile::fake()->image('gundam.jpg'),
            ]));

        $response->assertRedirect(route('back-office.produk.index'));
        $response->assertSessionHas('sukses');

        Storage::disk('public')->assertExists($this->produkDb()['gambar']);
        $this->assertDatabaseHas('produks', ['nama_produk' => 'Gundam RX-78-2']);
    }

    public function test_produk_menuntut_kategori_yang_valid(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post(route('back-office.produk.store'), $this->isian(['kategori_id' => 999]));

        $response->assertSessionHasErrors('kategori_id');
        $this->assertDatabaseCount('produks', 0);
    }

    public function test_harga_coret_harus_lebih_besar_dari_harga_jual(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post(route('back-office.produk.store'), $this->isian([
                'harga'       => 100000,
                'harga_coret' => 50000,
            ]));

        $response->assertSessionHasErrors('harga_coret');
        $this->assertDatabaseCount('produks', 0);
    }

    public function test_admin_bisa_memperbarui_produk(): void
    {
        $produk = Produk::create($this->isian());

        $response = $this->actingAs($this->adminUser())
            ->put(route('back-office.produk.update', $produk->id), $this->isian([
                'nama_produk' => 'Gundam Updated',
                'stok'        => 3,
            ]));

        $response->assertRedirect(route('back-office.produk.index'));
        $this->assertDatabaseHas('produks', [
            'id'           => $produk->id,
            'nama_produk'  => 'Gundam Updated',
            'stok'         => 3,
        ]);
    }

    public function test_admin_bisa_menghapus_produk(): void
    {
        $produk = Produk::create($this->isian());

        $response = $this->actingAs($this->adminUser())
            ->delete(route('back-office.produk.destroy', $produk->id));

        $response->assertRedirect(route('back-office.produk.index'));
        $this->assertDatabaseMissing('produks', ['id' => $produk->id]);
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('back-office.produk.index'))
            ->assertRedirect(route('back-office.login'));

        $this->post(route('back-office.produk.store'), $this->isian())
            ->assertRedirect(route('back-office.login'));

        $this->assertDatabaseCount('produks', 0);
    }

    public function test_halaman_publik_produk_tetap_berfungsi(): void
    {
        $produk = Produk::create($this->isian());

        $this->get(route('produk.index'))
            ->assertOk()
            ->assertViewIs('user_front.produk.index');

        $this->get(route('produk.show', $produk))
            ->assertOk()
            ->assertViewIs('user_front.produk.show');
    }
}