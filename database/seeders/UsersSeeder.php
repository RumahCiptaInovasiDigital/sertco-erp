<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'NIK' => 'EMP001',
                'fullname' => 'Ferdy Rahmat',
                'email' => 'ferdy.rahmat@sertco.co.id',
            ],
            [
                'NIK' => 'EMP002',
                'fullname' => 'Eka Pratama',
                'email' => 'eka.pratama@sertco.co.id',
            ],
            [
                'NIK' => 'EMP003',
                'fullname' => 'Fuad Alamsyah',
                'email' => 'fuad.alamsyah@sertco.co.id',
            ],
            [
                'NIK' => 'EMP004',
                'fullname' => 'Tommi Saputra',
                'email' => 'tommi.saputra@sertco.co.id',
            ],
            [
                'NIK' => 'EMP005',
                'fullname' => 'Dewi Lestari',
                'email' => 'dewi.lestari@sertco.co.id',
            ],
            [
                'NIK' => 'EMP006',
                'fullname' => 'Rizky Ramadhan',
                'email' => 'rizky.ramadhan@sertco.co.id',
            ],
            [
                'NIK' => 'EMP007',
                'fullname' => 'Andi Setiawan',
                'email' => 'andi.setiawan@sertco.co.id',
            ],
            [
                'NIK' => 'EMP008',
                'fullname' => 'Putri Handayani',
                'email' => 'putri.handayani@sertco.co.id',
            ],
            [
                'NIK' => 'EMP009',
                'fullname' => 'Rina Marlina',
                'email' => 'rina.marlina@sertco.co.id',
            ],
            [
                'NIK' => 'EMP010',
                'fullname' => 'Agus Santoso',
                'email' => 'agus.santoso@sertco.co.id',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'id_user' => (string) Str::uuid(),
                'NIK' => $user['NIK'],
                'fullname' => $user['fullname'],
                'email' => $user['email'],
                'jobLvl' => 'Karyawan',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
