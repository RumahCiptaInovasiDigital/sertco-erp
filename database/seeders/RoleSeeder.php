<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $roles = [
            'Direktur',
            'HRD Coordinator',
            'HRD Staff',
            'GA Staff',
            'IT Staff',
            'Messenger/OB',
            'Driver',
            'Marketing Manager',
            'Marketing Coordinator',
            'Marketing Staff',
            'T&O General Manager',
            'T&O Asisten Manager',
            'T&O Coordinator',
            'Inspector',
            'Reporting',
            'Project Controller',
            'Document Controller',
            'Finance A&T Manager',
            'Cost Controller',
            'Finance Staff',
            'Admin Purchasing Coordinator',
            'Admin Purchasing Staff',
            'HSE Coordinator',
            'HSE Staff',
            'QHSE Logistik',
        ];

        foreach ($roles as $name) {
            DB::table('roles')->insert([
                'id_role' => Str::uuid(),
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
