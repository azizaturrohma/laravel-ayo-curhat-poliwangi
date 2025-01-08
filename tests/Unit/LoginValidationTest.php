<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;

class LoginValidationTest extends TestCase
{
    /**
     * 9. Test jika form login dikirim tanpa username dan password.
     */
    public function test_login_empty_username_and_password()
    {
        $request = Request::create('/login', 'POST', [
            'name' => '',
            'password' => ''
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Formulir tidak boleh kosong! Masukkan Username dan Password.', session('error'));
    }

    /**
     *4. Test jika form login dikirim username kosong.
     */
    public function test_login_empty_username()
    {
        $request = Request::create('/login', 'POST', [
            'name' => '',
            'password' => 'password123'
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Username tidak boleh kosong!', session('error'));
    }

    /**
     *5.  Test jika form login dengan password yang kosong.
     */
    public function test_login_empty_password()
    {
        $request = Request::create('/login', 'POST', [
            'name' => 'username',
            'password' => ''
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Password tidak boleh kosong!', session('error'));
    }

    /**
     *10. Test jika username mengandung angka atau simbol.
     */
    public function test_login_invalid_username()
    {
        $request = Request::create('/login', 'POST', [
            'name' => 'user123!',
            'password' => 'password123'
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Username tidak valid!', session('error'));
    }

    /**
     *11. Test jika password kurang dari 8 karakter.
     */
    public function test_login_password_too_short()
    {
        $request = Request::create('/login', 'POST', [
            'name' => 'username',
            'password' => 'pass'
        ]);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Password tidak boleh kurang dari 8 karakter!', session('error'));
    }

    /**
 * Test jika username salah dan password benar.
 */
public function test_login_wrong_username_correct_password()
{
    $request = Request::create('/login', 'POST', [
        'name' => 'wrongusername',
        'password' => 'correctpassword123'
    ]);

    // Mock Auth::attempt untuk mengembalikan false karena username salah
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['name' => 'wrongusername', 'password' => 'correctpassword123'])
        ->andReturn(false);

    $controller = new LoginController();
    $response = $controller->login($request);

    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals('Username / Password Salah!', session('error'));
}

/**
 * Test jika username benar dan password salah.
 */
public function test_login_correct_username_wrong_password()
{
    $request = Request::create('/login', 'POST', [
        'name' => 'correctusername',
        'password' => 'wrongpassword'
    ]);

    // Mock Auth::attempt untuk mengembalikan false karena password salah
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['name' => 'correctusername', 'password' => 'wrongpassword'])
        ->andReturn(false);

    $controller = new LoginController();
    $response = $controller->login($request);

    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals('Username / Password Salah!', session('error'));
}

/**
 * Test jika username salah dan password salah.
 */
public function test_login_wrong_username_wrong_password()
{
    $request = Request::create('/login', 'POST', [
        'name' => 'wrongusername',
        'password' => 'wrongpassword'
    ]);

    // Mock Auth::attempt untuk mengembalikan false karena kedua kredensial salah
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['name' => 'wrongusername', 'password' => 'wrongpassword'])
        ->andReturn(false);

    $controller = new LoginController();
    $response = $controller->login($request);

    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals('Username / Password Salah!', session('error'));
}

}
