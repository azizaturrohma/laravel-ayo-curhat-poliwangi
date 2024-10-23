<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'Login';

        return view('auth.login', compact('title'));
    }

    public function login(Request $request)
    {

        // Cek jika keduanya kosong
        if (empty($request->name) && empty($request->password)) {
            return redirect()->back()->with('error', 'Formulir tidak boleh kosong! Masukkan Username dan Password.');
        }

        // Cek jika hanya username kosong
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'Username tidak boleh kosong!');
        }

        // Cek jika hanya password kosong
        if (empty($request->password)) {
            return redirect()->back()->with('error', 'Password tidak boleh kosong!');
        }

        // Cek jika username mengandung angka atau simbol
        if (!preg_match('/^[a-zA-Z\s]+$/', $request->name)) {
            return redirect()->back()->with('error', 'Username tidak valid!');
        }

        // Cek jika password tidak valid (kurang dari 8)
        // if (!preg_match('/^.{8,}$/', $request->password)) {
        //     return redirect()->back()->with('error', 'Password harus minimal 8 karakter!');
        // }

         // Cek jika username dan password tidak valid
    // if (!preg_match('/^[a-zA-Z\s]+$/', $request->name) && strlen($request->password) < 8) {
    //     return redirect()->back()->with('error', 'Username dan password tidak valid!');
    // }

        // $request->validate([
        //     'name' => [
        //     'required',
        //     'regex:/^[a-zA-Z]+$/',
        //     'min:5',
        //     'max:5'
        // ],
        //     'password' => 'required',
        // ], [
        //     'password.required' => 'Login Gagal! Password Tidak Boleh Kosong',
        //     'name.required' => 'Login Gagal! Username Tidak Valid'
        // ]);

        if (Auth::attempt($request->only('name', 'password'))) {
            if (Auth::user()->user_status != 'active') {
                Auth::logout();
                return redirect()->back()->with('error', 'Akun anda di non aktifkan');
            }
            return redirect()->intended('/');
        } else {
            return redirect()->back()->with('error', 'Username / Password Salah!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
