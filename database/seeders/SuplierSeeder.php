<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('supliers')->insert([
            'id_suplier' => \Str::uuid(),
            'nama_suplier' => 'Suplier 1',
            'telp_suplier' => '081234567890',
            'alamat_suplier' => 'Sample alamat suplier',
            'email_suplier' => 'suplier1@gmail.com',
            'norek_suplier'=> '12345678910',
            'bank_suplier'=> 'Bank Sample',
            'nama_kontak'=> 'Kontak Suplier 1',
            'nohp_kontak'=> '081298765432',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('supliers')->insert([
            'id_suplier' => \Str::uuid(),
            'nama_suplier' => 'Suplier 2',
            'telp_suplier' => '089876543210',
            'alamat_suplier' => 'Sample alamat suplier 2',
            'email_suplier' => 'suplier2@gmail.com',
            'norek_suplier'=> '10987654321',
            'bank_suplier'=> 'Bank Contoh',
            'nama_kontak'=> 'Kontak Suplier 2',
            'nohp_kontak'=> '089212345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
