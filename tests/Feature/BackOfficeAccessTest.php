<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackOfficeAccessTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create([
            'name' => 'Admin Wibu Store',
            'email' => 'admin@wibustore.com',
            'password' => 'admin123',
            'role' => 'admin',
        ]);
    }

    private function regularUser(): User
    {
        return User::factory()->create([
            'name' => 'Budi User',
            'email' => 'user@wibustore.com',
            'password' => 'password',
            'role' => 'user',
        ]);
    }

    public function test_halaman_login_back_office_tampil(): void
    {
        $response = $this->get(route('back-office.login'));

        $response->assertOk()
            ->assertSee('Masuk Admin')
            ->assertSee('Email')
            ->assertSee('Password');
    }

    public function test_tamu_dialihkan_ke_login_saat_mengakses_dashboard(): void
    {
        $response = $this->get(route('back-office.dashboard'));

        $response->assertRedirect(route('back-office.login'));
    }

    public function test_admin_bisa_login_dan_mengakses_dashboard(): void
    {
        $this->adminUser();

        $response = $this->post(route('back-office.login.authenticate'), [
            'email' => 'admin@wibustore.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('back-office.dashboard'));
        $this->assertAuthenticated();

        $this->get(route('back-office.dashboard'))
            ->assertOk()
            ->assertSee('Selamat datang, Admin Wibu Store!')
            ->assertSee('Total Produk')
            ->assertSee('Admin Aktif');
    }

    public function test_login_dengan_password_salah_gagal(): void
    {
        $this->adminUser();

        $response = $this->post(route('back-office.login.authenticate'), [
            'email' => 'admin@wibustore.com',
            'password' => 'salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pengguna_biasa_tidak_boleh_login_back_office(): void
    {
        $this->regularUser();

        $response = $this->post(route('back-office.login.authenticate'), [
            'email' => 'user@wibustore.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pengguna_biasa_terkunci_dari_halaman_dashboard(): void
    {
        $user = $this->regularUser();

        $response = $this->actingAs($user)->get(route('back-office.dashboard'));

        $response->assertForbidden();
    }

    public function test_logout_mengembalikan_ke_halaman_login(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)->post(route('back-office.logout'));

        $response->assertRedirect(route('back-office.login'));
        $this->assertGuest();
    }

    public function test_is_admin_mengembalikan_nilai_benar(): void
    {
        $this->assertTrue($this->adminUser()->isAdmin());
        $this->assertFalse($this->regularUser()->isAdmin());
    }
}
