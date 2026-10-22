<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Admin
        User::create([
            'name' => 'Admin Lab',
            'email' => 'admin@biomedika.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Buat Akun User / Mahasiswa biasa
        User::create([
            'name' => 'Mahasiswa Uji',
            'email' => 'mahasiswa@biomedika.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
