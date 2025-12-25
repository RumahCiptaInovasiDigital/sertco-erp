<?php

namespace Database\Factories;

use App\Models\DataKaryawan;
use App\Models\UserCredential;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserCredentialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserCredential::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $karyawan = DataKaryawan::factory()->create();
        return [
            'nik' => $karyawan->nik,
            'pass' => Hash::make('password123'),
        ];
    }
}
