<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_dapat_login_dengan_kredensial_yang_benar()
    {
        // Membuat pengguna admin
        $admin = User::create([
            'name' => 'admin',
            'password' => bcrypt('1234'),
            'user_status' => 'active',
        ]);

        // Mencoba login
        $response = $this->post('/login', [
            'name' => 'admin',
            'password' => '1234',
        ]);

        // Memastikan admin terautentikasi dan dialihkan ke halaman yang tepat
        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_tidak_dapat_login_dengan_password_salah()
    {
        // Membuat pengguna admin
        User::create([
            'name' => 'admin',
            'password' => bcrypt('1234'),
            'user_status' => 'active',
        ]);

        // Mencoba login dengan password yang salah
        $response = $this->post('/login', [
            'name' => 'admin',
            'password' => 'salah',
        ]);

        // Memastikan admin tidak terautentikasi dan menerima pesan kesalahan
        $this->assertGuest();
        $response->assertRedirect()->with('error', 'Username / Password Salah!');
    }

    /** @test */
    public function admin_tidak_dapat_login_jika_akun_non_aktif()
    {
        // Membuat pengguna admin dengan status non-aktif
        $admin = User::create([
            'name' => 'admin',
            'password' => bcrypt('1234'),
            'user_status' => 'inactive',
        ]);

        // Mencoba login
        $response = $this->post('/login', [
            'name' => 'admin',
            'password' => '1234',
        ]);

        // Memastikan admin tidak terautentikasi dan menerima pesan kesalahan
        $this->assertGuest();
        $response->assertRedirect()->with('error', 'Akun anda di non aktifkan');
    }
}
