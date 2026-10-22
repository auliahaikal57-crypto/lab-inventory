<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Data Laptop 1
        Item::create([
            'name' => 'LAPTOP TOKO LAB',
            'brand' => 'ASUS',
            'type' => 'VIVOBOOK - A1404ZA',
            'serial_code' => 'ASUS-A1404',
            'status' => 'Tersedia'
        ]);

        // Data Laptop 2
        Item::create([
            'name' => 'LAPTOP DOSEN',
            'brand' => 'DELL',
            'type' => 'LATITUDE G715',
            'serial_code' => 'DELL-G715',
            'status' => 'Sedang Dipinjam'
        ]);

        // Data Kursi/Meja
        Item::create([
            'name' => 'MEJA LIPAT PRAKTIKUM',
            'brand' => 'CHITOSE',
            'type' => 'FOLDABLE DESK 120',
            'serial_code' => 'MEJA-01',
            'status' => 'Tersedia'
        ]);

        // Data Laptop 3
        Item::create([
            'name' => 'LAPTOP ASISTEN LAB',
            'brand' => 'HP',
            'type' => 'PROBOOK NX6120',
            'serial_code' => 'HP-PROBOOK',
            'status' => 'Dalam Perbaikan'
        ]);
    }
}
