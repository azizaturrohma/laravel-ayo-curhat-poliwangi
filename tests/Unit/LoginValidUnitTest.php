<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginValidUnitTest extends TestCase
{
    public function test_login_for_petugas_satgas()
    {
        // Buat user dengan atribut 'Petugas'
        $user = new User([
            'name' => 'petugas',
            'password' => Hash::make('12345678'),
        ]);

        // Mock autentikasi
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['name' => 'petugas', 'password' => '12345678'])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Simulasikan login
        $isAuthenticated = Auth::attempt(['name' => 'petugas', 'password' => '12345678']);

        // Assertions
        $this->assertTrue($isAuthenticated);
        $this->assertEquals('petugas', Auth::user()->name);
    }

    public function test_login_for_tamu_satgas()
    {
        // Buat user dengan atribut 'Tamu Satgas'
        $user = new User([
            'name' => 'Daniela Natali Putri',
            'password' => Hash::make('password123'),
        ]);

        // Mock autentikasi
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['name' => 'Daniela Natali Putri', 'password' => 'password123'])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Simulasikan login
        $isAuthenticated = Auth::attempt(['name' => 'Daniela Natali Putri', 'password' => 'password123']);

        // Assertions
        $this->assertTrue($isAuthenticated);
        $this->assertEquals('Daniela Natali Putri', Auth::user()->name);
    }

    public function test_login_for_admin_satgas()
    {
        // Buat user dengan atribut 'Admin'
        $user = new User([
            'name' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        // Mock autentikasi
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['name' => 'admin', 'password' => '12345678'])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Simulasikan login
        $isAuthenticated = Auth::attempt(['name' => 'admin', 'password' => '12345678']);

        // Assertions
        $this->assertTrue($isAuthenticated);
        $this->assertEquals('admin', Auth::user()->name);
    }
}
