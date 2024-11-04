<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TambahPetugasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and roles
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_store_petugas()
    {
        // Buat pengguna admin untuk autentikasi
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Autentikasi pengguna
        $this->actingAs($admin);

        // Data valid untuk pengujian
        $data = [
            'name' => 'Danielaa',
            'email' => 'danielaa@gmail.com',
            'phone_number' => '08123456780',
            'password' => 'Daniela123'
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number']
        ]);

        $user = User::where('email', $data['email'])->first();
        $this->assertTrue(Hash::check('Daniela123', $user->password));
    }

    public function test_store_petugas_with_duplicate_phone_number()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        User::factory()->create(['phone_number' => '089578900987']);

        $data = [
            'name' => 'Luffy',
            'email' => 'luffy12@gmail.com',
            'phone_number' => '089578900987',
            'password' => 'Luffy123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function test_store_petugas_with_duplicate_email()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        User::factory()->create(['email' => 'danielaa@gmail.com']);

        $data = [
            'name' => 'Niko Robin',
            'email' => 'danielaa@gmail.com',
            'phone_number' => '089578900983',
            'password' => 'Robin123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_store_petugas_with_invalid_email_format()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $data = [
            'name' => 'User Test',
            'email' => 'user.com',
            'phone_number' => '08123456781',
            'password' => 'UserTest123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_store_petugas_with_short_password()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $data = [
            'name' => 'User Test',
            'email' => 'user@example.com',
            'phone_number' => '08123456782',
            'password' => 'short'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_store_petugas_with_invalid_username()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $data = [
            'name' => 'Invalid@Name!',
            'email' => 'userwithsymbols@gmail.com',
            'phone_number' => '08123456783',
            'password' => 'ValidPassword123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_store_petugas_with_short_phone_number()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $data = [
            'name' => 'User Test',
            'email' => 'shortphone@gmail.com',
            'phone_number' => '0812345',
            'password' => 'ValidPassword123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function test_store_petugas_with_long_phone_number()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $data = [
            'name' => 'User Test',
            'email' => 'longphone@gmail.com',
            'phone_number' => '081234567890123',
            'password' => 'ValidPassword123'
        ];

        $response = $this->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['phone_number']);
    }
}
