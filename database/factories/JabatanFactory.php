<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class JabatanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jabatan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nama_jabatan' => $this->faker->jobTitle,
            'level' => $this->faker->randomElement(['Staff', 'Supervisor', 'Manager']),
        ];
    }
}
