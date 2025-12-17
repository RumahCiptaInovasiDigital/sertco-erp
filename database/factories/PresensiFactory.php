<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use App\Models\DataKaryawan;
use App\Models\JadwalKerja;
use App\Models\Presensi;
use App\Models\ShiftKerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PresensiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presensi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $karyawan = DataKaryawan::inRandomOrder()->first();
        $bo = BranchOffice::inRandomOrder()->first();
        $shift = ShiftKerja::query()->inRandomOrder()->first();

        return [
            'id' => Str::uuid(),
            'data_karyawan_id' => $karyawan->id,
            'tanggal' => $this->faker->dateTimeBetween('now', '+2 days')->format('Y-m-d'),
            'origin_branchoffice_masuk_id' => $bo->id,
            'origin_branchoffice_pulang_id' => $bo->id,
            'shift_kerja_id' => $shift->id,
            'jam_harus_masuk_awal' => $shift->jam_masuk_min,
            'jam_harus_masuk_akhir' => $shift->jam_masuk_max,
            'jam_harus_pulang_awal' => $shift->jam_pulang_min,
            'jam_harus_pulang_akhir' => $shift->jam_pulang_max,
            'type_presensi' => $shift->tipe,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Presensi $presensi) {
             $jadwal = JadwalKerja::query()->where("id_karyawan", $presensi->data_karyawan_id)->first();
             $shiftkerja = Shiftkerja::query()->where("id", $jadwal?->id_shift_kerja)->first();
             $presensi->shift_kerja_id = $shiftkerja?->id;
             $presensi->jam_harus_masuk_awal = $shiftkerja?->jam_masuk_min;
             $presensi->jam_harus_masuk_akhir = $shiftkerja?->jam_masuk_max;
             $presensi->jam_harus_pulang_awal = $shiftkerja?->jam_pulang_min;
             $presensi->jam_harus_pulang_akhir = $shiftkerja?->jam_pulang_max;
             $presensi->type_presensi = $shiftkerja?->tipe;
        });
    }
}

