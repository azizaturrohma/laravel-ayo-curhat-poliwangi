<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $tamuSatgas;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles dan permissions jika diperlukan
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Buat user dengan role 'Tamu Satgas'
        $this->tamuSatgas = User::factory()->create([
            'name' => 'Daniela Natali Putri',
            'password' => Hash::make('password123'),
            'user_status' => 'active'
        ]);

        // Tetapkan role 'Tamu Satgas'
        $this->tamuSatgas->assignRole('Tamu Satgas');
    }

    /** 4. Login dengan username kosong */
    public function test_login_with_empty_username()
    {
        $response = $this->post('/auth/login', [
            'name' => '',
            'password' => 'password123'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Username tidak boleh kosong!');
        $this->assertGuest();
    }

    /** 5. Login dengan password kosong */
    public function test_login_with_empty_password()
    {
        $response = $this->post('/auth/login', [
            'name' => 'Daniela Natali Putri',
            'password' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Password tidak boleh kosong!');
        $this->assertGuest();
    }

    /** 9. Login dengan username dan password kosong */
    public function test_login_with_empty_username_and_password()
    {
        $response = $this->post('/auth/login', [
            'name' => '',
            'password' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Formulir tidak boleh kosong! Masukkan Username dan Password.');
        $this->assertGuest();
    }

    /** 10. Login dengan username invalid (mengandung angka/simbol) */
    public function test_login_with_special_characters_in_username()
    {
        $response = $this->post('/auth/login', [
            'name' => 'Daniela12!',
            'password' => 'password123'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Username tidak valid!');
        $this->assertGuest();
    }

    /** 11. Login dengan password kurang dari 8 karakter */
    public function test_login_with_short_password()
    {
        $response = $this->post('/auth/login', [
            'name' => 'Daniela Natali Putri',
            'password' => 'short'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Password tidak boleh kurang dari 8 karakter!');
        $this->assertGuest();
    }

    /** 7. Login dengan username valid dan password salah */
    public function test_login_with_valid_username_and_invalid_password()
    {
        $response = $this->post('/auth/login', [
            'name' => 'Daniela Natali Putri',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Username / Password Salah!');
        $this->assertGuest();
    }

    /** 8. Login dengan username dan password yang salah */
    public function test_login_with_invalid_username_and_password()
    {
        $response = $this->post('/auth/login', [
            'name' => 'InvalidUser',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Username / Password Salah!');
        $this->assertGuest();
    }

     /** 6. Login dengan invalid username dan valid password */
    public function test_login_with_invalid_username_and_valid_password()
    {
        $response = $this->post('/auth/login', [
            'name' => 'miaw',
            'password' => 'Password123'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Username / Password Salah!');
        $this->assertGuest();
    }

}
