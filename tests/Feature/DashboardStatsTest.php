<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@wdashboard.test'],
            ['name' => 'Admin Wibu Store', 'password' => 'admin123', 'role' => 'admin'],
        );
    }

    private function produk(array $override = []): Produk
    {
        static $kategori = null;

        $kategori ??= Kategori::create(['nama_kategori' => 'Figure', 'slug' => 'figure']);

        return Produk::create(array_merge([
            'kategori_id' => $kategori->id,
            'nama_produk' => 'Produk '.uniqid(),
            'slug'        => uniqid('produk-'),
            'harga'       => 50000,
            'stok'        => 10,
            'berat'       => 300,
            'status'      => 'aktif',
        ], $override));
    }

    public function test_dashboard_menampilkan_nol_saat_database_kosong(): void
    {
        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.dashboard'));

        $response->assertOk();
        $response->assertSee('Total Produk');
        $response->assertSee('Kategori');
        $response->assertSee('Produk Aktif');
        $response->assertSee('Admin Aktif');

        // Angka hardcoded lama (jumlah data dummy) tidak boleh muncul lagi
        $response->assertDontSee('Pesanan Baru');

        $ringkasan = $response->viewData('ringkasan');
        $this->assertSame(0, $ringkasan['produk']);
        $this->assertSame(0, $ringkasan['kategori']);
        $this->assertSame(0, $ringkasan['produk_aktif']);
        $this->assertSame(1, $ringkasan['admin_aktif']);
    }

    public function test_dashboard_menghitung_produk_dan_kategori_dari_database(): void
    {
        $this->produk();
        $this->produk();
        $this->produk(['status' => 'nonaktif']);

        Kategori::create(['nama_kategori' => 'Gunpla', 'slug' => 'gunpla']);

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.dashboard'));

        $ringkasan = $response->viewData('ringkasan');

        // Total produk = semua baris, termasuk yang nonaktif
        $this->assertSame(3, $ringkasan['produk']);
        $this->assertSame(2, $ringkasan['kategori']);

        // Produk aktif hanya yang berstatus aktif
        $this->assertSame(2, $ringkasan['produk_aktif']);
    }

    public function test_dashboard_menghitung_semua_admin(): void
    {
        $this->adminUser();
        User::factory()->create([
            'email' => 'admin2@wdashboard.test',
            'role'  => 'admin',
        ]);
        User::factory()->create([
            'email' => 'pembeli@wdashboard.test',
            'role'  => 'user',
        ]);

        $response = $this->actingAs($this->adminUser())
            ->get(route('back-office.dashboard'));

        $this->assertSame(2, $response->viewData('ringkasan')['admin_aktif']);
    }
}