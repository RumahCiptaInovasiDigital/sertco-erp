<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jabatans')->insert([
            // Level 8 - Director
            ['nama_jabatan' => 'Director', 'level' => '8'],
            ['nama_jabatan' => 'Business Development Director', 'level' => '8'],

            // Level 7 - Manager
            ['nama_jabatan' => 'Manager', 'level' => '7'],
            ['nama_jabatan' => 'Operation Manager', 'level' => '7'],
            ['nama_jabatan' => 'Technical & Operation Manager', 'level' => '7'],
            ['nama_jabatan' => 'Bid & Admin Manager', 'level' => '7'],

            // Level 6 - Coordinator
            ['nama_jabatan' => 'Coordinator', 'level' => '6'],
            ['nama_jabatan' => 'Technical & Operation Coordinator', 'level' => '6'],
            ['nama_jabatan' => 'HRD & Admin Coordinator', 'level' => '6'],
            ['nama_jabatan' => 'HR & Office Admin Coordinator', 'level' => '6'],
            ['nama_jabatan' => 'Testing Coordinator', 'level' => '6'],
            ['nama_jabatan' => 'Marketing Coordinator', 'level' => '6'],

            // Level 5 - Controller & Specialist
            ['nama_jabatan' => 'Financial Controller', 'level' => '5'],
            ['nama_jabatan' => 'Support Specialist', 'level' => '5'],

            // Level 4 - Staff
            ['nama_jabatan' => 'Staff', 'level' => '4'],
            ['nama_jabatan' => 'Staff Operasional', 'level' => '4'],
            ['nama_jabatan' => 'Marketing Staff', 'level' => '4'],
            ['nama_jabatan' => 'HRD & Admin Staff', 'level' => '4'],
            ['nama_jabatan' => 'Finance Staff', 'level' => '4'],
            ['nama_jabatan' => 'Admin Staff', 'level' => '4'],
            ['nama_jabatan' => 'HSE Staff', 'level' => '4'],
            ['nama_jabatan' => 'Document Control', 'level' => '4'],

            // Level 3 - Officer
            ['nama_jabatan' => 'HSE Officer', 'level' => '3'],

            // Level 2 - Inspector
            ['nama_jabatan' => 'Inspector', 'level' => '2'],
            ['nama_jabatan' => 'Junior Inspector', 'level' => '2'],

            // Level 1 - Support
            ['nama_jabatan' => 'Receptionist', 'level' => '1'],
            ['nama_jabatan' => 'Messenger & Admin', 'level' => '1'],
            ['nama_jabatan' => 'Security', 'level' => '1'],
        ]);
    }
}
