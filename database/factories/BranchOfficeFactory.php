<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BranchOfficeFactory extends Factory
{
    protected $model = BranchOffice::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'fax' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'coordinates' => [
                ['lat' => $this->faker->latitude(),
                'lng' => $this->faker->longitude(),
                'radius' => $this->faker->numberBetween(10, 100),]
            ],
            'ip_registered' => [$this->faker->ipv4()],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
