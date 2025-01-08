<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginValidTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and roles
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Optionally bypass middleware
        $this->withoutMiddleware();
    }

    public function test_login_for_petugas_satgas()
    {
        // Create user with 'Petugas' role
        $petugas = User::factory()->create([
            'name' => 'petugas',
            'password' => Hash::make('12345678'),
        ]);
        $petugas->assignRole('Petugas');

        // Attempt login with valid credentials
        $response = $this->post('/auth/login', [
            'name' => 'petugas',
            'password' => '12345678',
        ]);

        // Assert successful redirection to intended route
        $response->assertStatus(302)->assertRedirect('/');
        $this->assertAuthenticatedAs($petugas);
    }

    public function test_login_for_tamu_satgas()
    {
        // Create user with 'Tamu Satgas' role
        $tamuSatgas = User::factory()->create([
            'name' => 'Daniela Natali Putri',
            'password' => Hash::make('password123'),
        ]);
        $tamuSatgas->assignRole('Tamu Satgas');

        // Attempt login with valid credentials
        $response = $this->post('/auth/login', [
            'name' => 'Daniela Natali Putri',
            'password' => 'password123',
        ]);

        // Assert successful redirection to intended route
        $response->assertStatus(302)->assertRedirect('/');
        $this->assertAuthenticatedAs($tamuSatgas);
    }

    public function test_login_for_admin_satgas()
    {
        // Create user with 'Admin' role
        $adminSatgas = User::factory()->create([
            'name' => 'admin',
            'password' => Hash::make('12345678'),
        ]);
        $adminSatgas->assignRole('Admin');

        // Attempt login with valid credentials
        $response = $this->post('/auth/login', [
            'name' => 'admin',
            'password' => '12345678',
        ]);

        // Assert successful redirection to intended route
        $response->assertStatus(302)->assertRedirect('/');
        $this->assertAuthenticatedAs($adminSatgas);
    }
}
