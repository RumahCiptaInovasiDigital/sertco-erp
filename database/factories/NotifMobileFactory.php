<?php

namespace Database\Factories;

use App\Models\DataKaryawan;
use App\Models\Enum\StatusNotif;
use App\Models\NotifMobile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotifMobileFactory extends Factory
{
    protected $model = NotifMobile::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement( StatusNotif::values() ),
            'category' => $this->faker->randomElement( ['info', 'warning', 'alert', 'promo', 'system', 'event'] ),
            'karyawan_id' => DataKaryawan::inRandomOrder()->first()->id,
            'created_at' => now(),
        ];
    }
}
