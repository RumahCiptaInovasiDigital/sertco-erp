<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('satuan_barangs')->insert([
            'id_satuan_barang' => \Str::uuid(),
            'satuan' => 'pcs',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('satuan_barangs')->insert([
            'id_satuan_barang' => \Str::uuid(),
            'satuan' => 'rim',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
