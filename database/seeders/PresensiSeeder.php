<?php

namespace Database\Seeders;

use App\Models\DataKaryawan;
use App\Models\Presensi;
use App\Models\UserCredential;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $krys = DataKaryawan::all();
        $tgl1 = array_map(function ($k) {
            return now()->subDays($k)->format('Y-m-d');
        }, range(1,5));

        $tgl = array_map(function ($k) {
            return now()->addDays($k)->format('Y-m-d');
        }, range(0,5));

        $tgl = array_merge($tgl1,$tgl);

        foreach ($krys as $kry) {
            foreach($tgl as $t) {
                $data = [
                    'data_karyawan_id' => $kry->id,
                    'tanggal' => $t,
                ];
                $dt = Carbon::parse($t);
                if(now()->greaterThan($dt)) {
                    $data['jam_masuk'] = fake()->time("H:i:s", "09:00:00");
                    $data['jam_pulang'] = fake()->time("H:i:s", "17:00:00");
                    $data['koordinat_masuk'] = fake()->latitude() . ',' . fake()->longitude();
                    $data['koordinat_pulang'] = fake()->latitude() . ',' . fake()->longitude();

                }else{

                }
                Presensi::factory()->create($data);
            }
        }
    }
}
