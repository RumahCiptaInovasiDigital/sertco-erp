<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('vendors')->insert([
            'id_vendor' => \Str::uuid(),
            'nama_vendor' => 'Vendor 1',
            'telp_vendor' => '081234567890',
            'alamat_vendor' => 'Sample alamat vendor 1',
            'email_vendor' => 'vendor1@gmail.com',
            'norek_vendor'=> '12345678910',
            'bank_vendor'=> 'Bank Sample',
            'nama_kontak_vendor'=> 'Kontak vendor 1',
            'nohp_kontak_vendor'=> '081298765432',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('vendors')->insert([
            'id_vendor' => \Str::uuid(),
            'nama_vendor' => 'Vendor 2',
            'telp_vendor' => '089876543210',
            'alamat_vendor' => 'Sample alamat vendor 2',
            'email_vendor' => 'vendor2@gmail.com',
            'norek_vendor'=> '10987654321',
            'bank_vendor'=> 'Bank Contoh',
            'nama_kontak_vendor'=> 'Kontak vendor 2',
            'nohp_kontak_vendor'=> '089212345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
