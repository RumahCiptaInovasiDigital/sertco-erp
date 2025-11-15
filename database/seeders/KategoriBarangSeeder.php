<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('kategori_barangs')->insert([
            'id_kategori_barang' => \Str::uuid(),
            'nama_kategori' => 'Air Conditioner',
            'maintenance' => 'Y',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('kategori_barangs')->insert([
            'id_kategori_barang' => \Str::uuid(),
            'nama_kategori' => 'ATK',
            'maintenance' => 'T',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
