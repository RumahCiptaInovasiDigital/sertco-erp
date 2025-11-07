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
        $jabatans = [
            'Acc & Finance Staff IINDT',
            'Accounting & Finance Staff',
            'Admin',
            'Admin & Purchasing Coordinator',
            'Admin & Purchasing Staff',
            'Admin Finance',
            'Admin HRD Staff',
            'Admin Purchasing',
            'Admin Staff',
            'Bid Staff',
            'Business Development Director',
            'Coordinator Admin Purchasing',
            'Cost Control Coordinator',
            'Director',
            'Document Control',
            'Driver',
            'Engineering',
            'Finance',
            'Finance Coordinator',
            'Finance Staff',
            'Finance Staff (PB)',
            'Financial Controller Manager',
            'Financial Controller Staff',
            'GA Staff',
            'General Manager',
            'Head Director',
            'HR & GA Staff',
            'HR Staff',
            'HRD & Admin Coordinator',
            'HRD & GA Staff',
            'HRD Koordinator',
            'HRGA Coordinator',
            'HSE & MR Coordinator',
            'HSE Coordinator',
            'HSE Officer',
            'HSE Staff',
            'IINDT',
            'Inspector',
            'Inspektor',
            'Intesco',
            'IT Staff',
            'IT Support',
            'Logistic Staff',
            'Manager BID Marketing',
            'Marketing Coordinator',
            'Marketing Staff',
            'Messenger',
            'Messenger & Admin',
            'Messenger Staff',
            'MR & QHSE Ass. Men',
            'NDT Coordinator',
            'OB',
            'OB & Messenger',
            'Project Control Staff',
            'Purchasing & Admin Staff',
            'Purchasing Staff',
            'Purchasing Staff (PB)',
            'QHSE & MR Coordinator',
            'QHSE Coordinator',
            'QHSE Officer',
            'Receptionist',
            'Reporting Staff',
            'Security',
            'Senior RIG Inspector',
            'Senior Staff HRD',
            'Senior Staff HSE',
            'Staff Admin',
            'Staff HR',
            'Staff HR Legal',
            'Tax & Accounting Staff',
            'Technical & Operation',
            'Technical & Operation Ass. Manager',
            'Technical & Operation Coordinator',
            'Technical & Operation Director',
            'Technical & Operation Reporting Coordinator',
            'Testing',
            'Welding Inspector',
        ];

        foreach ($jabatans as $name) {
            DB::table('roles')->insert([
                'id_role' => Str::uuid(),
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
