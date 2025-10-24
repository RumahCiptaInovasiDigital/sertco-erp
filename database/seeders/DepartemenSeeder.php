<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemens = [
            'Marketing',
            'T&O',
            'Finance',
            'HRGA IT',
            'Admin Purchasing',
            'HSE',
        ];

        foreach ($departemens as $name) {
            \DB::table('departemens')->insert([
                'id_departemen' => \Str::uuid(),
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
