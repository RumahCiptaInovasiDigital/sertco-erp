<?php

namespace Database\Seeders;

use App\Models\ShiftKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ShiftKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [];
        for ($i = 1; $i <= 8; $i++) {
            $shifts[] = [
                'id' => Str::uuid(),
                'nama_shift' => 'Shift Level ' . $i,
                'jam_masuk_min' => '08:00:00',
                'jam_masuk_max' => '09:00:00',
                'jam_pulang_min' => '17:00:00',
                'jam_pulang_max' => '18:00:00',
                'tipe' => rand(0, 1) ? 'WFO' : 'WFA',
                'berlaku_untuk' => 'Level ' . $i,
                'status' => 'Aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        ShiftKerja::query()->insert($shifts);
    }
}
