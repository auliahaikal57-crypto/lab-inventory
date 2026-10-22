<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Mendaftarkan Akun Super Admin Utama Lab Biomedika
        User::create([
            'name' => 'Super Admin Biomedika',
            'email' => 'admin@biomedika.id',         // Menggunakan email pilihan Anda
            'password' => Hash::make('stei1098'),  // Password rahasia Anda untuk login
            'role' => 'super_admin',                  // Diset sebagai tingkatan tertinggi
        ]);
    }
}
