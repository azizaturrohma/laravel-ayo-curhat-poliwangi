<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EditDataPetugasValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and roles
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_edit_data_petugas_with_existing_email()
    {
        // Buat pengguna admin untuk autentikasi
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Buat pengguna dengan email yang sudah terdaftar
        User::factory()->create(['email' => 'daniela12@gmail.com']);

        // Buat pengguna petugas yang akan di-edit
        $petugas = User::factory()->create();

        $this->actingAs($admin);

        // Data dengan email yang sudah terdaftar
        $data = [
            'name' => 'Desi Ayu',
            'email' => 'daniela12@gmail.com',
            'phone_number' => '081209876512',
            'password' => 'Daniela25'
        ];

        $response = $this->patch(route('users.update', $petugas->id), $data);

        // Memeriksa pesan kesalahan untuk email yang sudah terdaftar
        $response->assertSessionHasErrors(['email' => 'Email ini sudah terdaftar.']);
    }
    public function test_edit_data_petugas_with_existing_phone_number()
    {
        // Buat pengguna admin untuk autentikasi
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Buat pengguna dengan nomor telepon yang sudah terdaftar
        User::factory()->create(['phone_number' => '081234567890']);

        // Buat pengguna petugas yang akan di-edit
        $petugas = User::factory()->create(['phone_number' => '0812098765432']);

        // Data dengan nomor telepon yang sudah terdaftar
        $data = [
            'name' => 'Natali Putri',
            'email' => 'daniela25@gmail.com',
            'phone_number' => '081234567890', // Nomor telepon yang sudah ada
            'password' => 'Daniela25',
        ];

        // Kirim permintaan PATCH untuk memperbarui data petugas
        $response = $this->actingAs($admin)->patch(route('users.update', $petugas->id), $data);

        // Memeriksa pesan kesalahan untuk nomor telepon yang sudah terdaftar
        $response->assertSessionHasErrors(['phone_number' => 'Nomor telepon ini sudah terdaftar.']);
    }


    public function test_edit_data_petugas_with_invalid_email_format()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $petugas = User::factory()->create();

        $this->actingAs($admin);

        // Data dengan format email yang salah
        $data = [
            'name' => 'Daniela Natali',
            'email' => 'daniela12.gmail.com]', // Format salah
            'phone_number' => '0812098765432',
            'password' => 'Daniela25'
        ];

        $response = $this->patch(route('users.update', $petugas->id), $data);

        $response->assertSessionHasErrors(['email' => 'Format email tidak valid.']);
    }

    public function test_edit_data_petugas_with_short_password()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $petugas = User::factory()->create();

        $this->actingAs($admin);

        // Data dengan kata sandi kurang dari 6 karakter
        $data = [
            'name' => 'Daniela Natali',
            'email' => 'daniela12@gmail.com',
            'phone_number' => '0812098765432',
            'password' => 'Niel09' // Password kurang dari 6 karakter
        ];

        $response = $this->patch(route('users.update', $petugas->id), $data);

        $response->assertSessionHasErrors(['password' => 'Password harus memiliki minimal 6 karakter.']);
    }
}
