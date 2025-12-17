<?php

namespace Database\Seeders;

use App\Models\DataKaryawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kry = DataKaryawan::query()->get(["id"]);
        foreach ($kry as $k) {
            \App\Models\JadwalKerja::factory()->create([
                'id_karyawan' => $k->id,
            ]);
        }
    }
}
