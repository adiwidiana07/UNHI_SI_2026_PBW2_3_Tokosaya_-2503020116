<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create([
            'name'     => 'Admin Wibu Store',
            'email'    => 'admin@wategori.test',
            'password' => 'admin123',
            'role'     => 'admin',
        ]);
    }

    private function regularUser(): User
    {
        return User::factory()->create([
            'name'     => 'Budi User',
            'email'    => 'user@wategori.test',
            'password' => 'password',
            'role'     => 'user',
        ]);
    }

    public function test_halaman_kategori_menampilkan_daftar(): void
    {
        Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.kategori.index'));

        $response->assertOk();
        $response->assertViewIs('back_office.kategori.index');
        $response->assertSee('Makanan');
    }

    public function test_admin_bisa_menambah_kategori(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->post(route('back-office.kategori.store'), [
                'nama_kategori' => 'Minuman',
                'deskripsi'     => 'Segala jenis minuman',
            ]);

        $response->assertRedirect(route('back-office.kategori.index'));
        $response->assertSessionHas('sukses');
        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Minuman',
            'slug'          => 'minuman',
        ]);
    }

    public function test_nama_kategori_harus_unik(): void
    {
        Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->adminUser())
            ->post(route('back-office.kategori.store'), [
                'nama_kategori' => 'Makanan',
            ]);

        $response->assertSessionHasErrors('nama_kategori');
        $this->assertSame(1, Kategori::count());
    }

    public function test_halaman_edit_kategori_tampil(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.kategori.edit', $kategori->id));

        $response->assertOk();
        $response->assertViewIs('back_office.kategori.edit');
        $response->assertSee('Makanan');
    }

    public function test_admin_bisa_memperbarui_kategori(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->adminUser())
            ->put(route('back-office.kategori.update', $kategori->id), [
                'nama_kategori' => 'Camilan',
                'deskripsi'     => 'Makanan ringan',
            ]);

        $response->assertRedirect(route('back-office.kategori.index'));
        $response->assertSessionHas('sukses');
        $this->assertDatabaseHas('kategoris', [
            'id'            => $kategori->id,
            'nama_kategori' => 'Camilan',
            'slug'          => 'camilan',
        ]);
    }

    public function test_admin_bisa_menghapus_kategori_kosong(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);

        $response = $this->actingAs($this->adminUser())
            ->delete(route('back-office.kategori.destroy', $kategori->id));

        $response->assertRedirect(route('back-office.kategori.index'));
        $response->assertSessionHas('sukses');
        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }

    public function test_kategori_yang_masih_punya_produk_tidak_bisa_dihapus(): void
    {
        $kategori = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);
        Produk::create([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Nasi Goreng',
            'slug'        => 'nasi-goreng',
            'harga'       => 15000,
            'stok'        => 10,
        ]);

        $response = $this->actingAs($this->adminUser())
            ->delete(route('back-office.kategori.destroy', $kategori->id));

        $response->assertRedirect(route('back-office.kategori.index'));
        $response->assertSessionHas('gagal');
        $this->assertDatabaseHas('kategoris', ['id' => $kategori->id]);
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('back-office.kategori.index'))
            ->assertRedirect(route('back-office.login'));

        $this->post(route('back-office.kategori.store'), ['nama_kategori' => 'Minuman'])
            ->assertRedirect(route('back-office.login'));

        $this->assertDatabaseMissing('kategoris', ['nama_kategori' => 'Minuman']);
    }

    public function test_pengguna_biasa_tidak_boleh_mengelola_kategori(): void
    {
        $user = $this->regularUser();

        $this->actingAs($user)
            ->get(route('back-office.kategori.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('back-office.kategori.store'), ['nama_kategori' => 'Minuman'])
            ->assertForbidden();

        $this->assertDatabaseMissing('kategoris', ['nama_kategori' => 'Minuman']);
    }
}