<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'password' => Hash::make(12345678),
        ]);

        $admin->assignRole('Admin');

        // $petugas = [
        //     [
        //         'name' => 'petugas',
        //         'password'=> Hash::make(1234),
        //     ],
        //     [
        //         'name' => 'petugas2',
        //         'password'=> Hash::make(1234),
        //     ],
        // ];

        // foreach ($petugas as $ptg) {
        //     $user = User::create($ptg);
        //     $user->assignRole('Petugas');
        // }

        $tamuSatgasUsers = [
            [
                'name' => 'Azizatur Rohma',
                'password' => Hash::make(1234),
                'gender' => 'perempuan',
                'email' => 'azizaturrohmablt234@gmail.com',
                'phone_number' => '082282560426',
                'complete_address' => 'Jalan Hank Ogan Desa Tugu Harum Kecamatan Ogan Komering Ulu Timur, Sumatera Selatan',
            ],
            [
                'name' => 'David Mahbubi',
                'password' => Hash::make(1234),
                'gender' => 'laki-laki',
                'email' => 'ulrichdavid@gmail.com',
                'phone_number' => '081234567890',
                'complete_address' => 'Durun Krajan Desa Pakistaji Kecamatan Kabat Kabupaten Banyuwangi, Jawa Timur',
            ],
            [
                'name' => 'Desi Ayu Trisnowati',
                'password' => Hash::make(1234),
                'gender' => 'perempuan',
                'email' => 'desiayuth@gmail.com',
                'phone_number' => '085768005286',
                'complete_address' => 'Desa Gladag Kecamatan Rogojampi Kabupaten Banyuwangi, Jawa Timur',
            ],
            [
                'name' => 'Novan Rohman Nur Khoir',
                'password' => Hash::make(1234),
                'gender' => 'laki-laki',
                'email' => 'novan@gmail.com',
                'phone_number' => '085877775286',
                'complete_address' => 'Desa Karangbendo Kecamatan Rogojampi Kabupaten Banyuwangi, Jawa Timur',
            ],
        ];

        foreach ($tamuSatgasUsers as $userData) {
            $user = User::create($userData);
            $user->assignRole('Tamu Satgas');
        }
    }
}
