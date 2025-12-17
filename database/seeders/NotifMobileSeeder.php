<?php

namespace Database\Seeders;

use App\Models\DataKaryawan;
use App\Models\NotifMobile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotifMobileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = DataKaryawan::all();
        foreach ($karyawan as $k) {
            NotifMobile::factory()->count(5)->create([
                'karyawan_id' => $k->id,
            ]);
        }
    }
}
