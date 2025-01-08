<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EditDataPetugasValidTest extends TestCase

{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and roles
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

/** @test */
    public function test_edit_data_petugas_with_valid_information()
    {
        // Buat pengguna admin untuk autentikasi
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Buat pengguna petugas yang akan di-edit
        $petugas = User::factory()->create([
            'name' => 'Petugas Lama',
            'email' => 'petugaslama@gmail.com',
            'phone_number' => '08123456789',
            'password' => bcrypt('PetugasLama123')
        ]);

        // Autentikasi pengguna sebagai admin
        $this->actingAs($admin);

        // Data baru yang valid untuk pengujian
        $data = [
            'name' => 'Daniela Natali',
            'email' => 'daniela12@gmail.com',
            'phone_number' => '0812098765432',
            'password' => 'Daniela25'
        ];

        // Lakukan permintaan PATCH untuk mengupdate profil
        $response = $this->patch(route('users.update', $petugas->id), $data);

        // Periksa apakah berhasil redirect ke halaman index pengguna
        $response->assertRedirect(route('users.index'));

        // Periksa apakah data baru tersimpan di database
        $this->assertDatabaseHas('users', [
            'id' => $petugas->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
        ]);

        // Periksa apakah password baru di-hash dengan benar
        $updatedPetugas = User::find($petugas->id);
        $this->assertTrue(Hash::check($data['password'], $updatedPetugas->password));
    }
}
