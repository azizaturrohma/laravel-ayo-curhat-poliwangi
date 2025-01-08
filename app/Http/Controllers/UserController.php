<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Petugas';

        $status = $request->input('status');
        $query = User::role('Petugas');

        if ($status == 'active') {
            $query->where('user_status', 'active');
        } elseif ($status == 'inactive') {
            $query->where('user_status', 'inactive');
        }

        $users = $query->get();

        return view('user.index', compact('title', 'users', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|min:5|max:40',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|regex:/^[0-9]+$/|unique:users,phone_number|min:11|max:13',
            'password' => 'required|min:8',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.regex' => 'Nama tidak boleh mengandung karakter atau simbol.',
            'name.min' => 'Panjang nama minimal 5 karakter.',
            'name.max' => 'Panjang nama maksimal 50 karakter.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon tidak boleh kosong.',
            'phone_number.max' => 'Nomor telepon maksimal 13 karakter.',
            'phone_number.min' => 'Nomor telepon minimal 11 karakter.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
            'phone_number.unique' => 'Nomor telepon ini sudah terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
        ]);

        try {
            // Simpan data ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('Petugas');
            session()->flash('success', 'Data berhasil disimpan!');
            return redirect()->route('users.index')->with('modal_type', null);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['store_error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput()
                ->with('modal_type', 'create');
     }
    }

    public function show(User $user)
    {
        $title = 'Detail Petugas';

        return view('user.show', compact('title', 'user'));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|min:5|max:40',
            'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
            'phone_number' => [
            'required',
            'regex:/^[0-9]+$/',
            'min:11',
            'max:13',
            Rule::unique('users', 'phone_number')->ignore($user->id),
        ],
            'password' => 'min:6|nullable',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.regex' => 'Nama tidak boleh mengandung karakter atau simbol.',
            'name.min' => 'Panjang nama minimal 5 karakter.',
            'name.max' => 'Panjang nama maksimal 50 karakter.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon tidak boleh kosong.',
            'phone_number.max' => 'Nomor telepon maksimal 13 karakter.',
            'phone_number.min' => 'Nomor telepon minimal 11 karakter.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
            'phone_number.unique' => 'Nomor telepon ini sudah terdaftar.',
           // 'password.min' => 'Password harus memiliki minimal 6 karakter.',
        ]);

        try {
            // Update user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);

            session()->flash('success', 'Data berhasil diperbarui!');
            return redirect()->route('users.index')->with(['modal_type' => 'edit', 'another_key' => null]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['update_error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])
                ->withInput()
                ->with('modal_type', 'edit');

        }
    }

    public function indexProfile(User $user)
    {
        $title = 'Profil Saya';

        return view('user.index-profile', compact('title', 'user'));
    }

    public function editProfile(User $user)
    {
        $title = 'Edit Profil Saya';

        return view('user.edit-profile', compact('title', 'user'));
    }

    public function updateProfile(Request $request)
{
    $request->validate([
        'email' => 'nullable|email|unique:users,email,' . auth()->user()->id,
        'phone_number' => 'required|unique:users,phone_number,min:11|max:13|regex:/^[0-9]+$/',
        'complete_address' => 'nullable|min:15',
    ], [
        'email.unique' => 'Email ini sudah terdaftar.',
        'email.email' => 'Format email tidak valid.',
        'phone_number.max' => 'Nomor telepon maksimal 13 karakter.',
        'phone_number.min' => 'Nomor telepon minimal 11 karakter.',
        'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
        'complete_address.min' => 'Alamat harus memiliki minimal 15 karakter.',
    ]);

    User::find(auth()->user()->id)->update([
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'complete_address' => $request->complete_address,
    ]);

    session()->flash('success', 'Profil berhasil diperbarui!');

    return redirect()->back();
}

    public function updateUserStatus(Request $request, $id)
    {
        $users = User::findOrFail($id);

        if ($request->status == 'active') {
            $users->user_status = 'active';
        } elseif ($request->status == 'inactive') {
            $users->user_status = 'inactive';
        }

        $users->save();

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
