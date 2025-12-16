<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalKerja>
 */
class JadwalKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_karyawan' => \App\Models\DataKaryawan::inRandomOrder()->first()->id,
            'id_shift_kerja' => \App\Models\ShiftKerja::inRandomOrder()->first()->id,
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
