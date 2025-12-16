<?php

namespace Database\Factories;

use App\Models\Enum\StatusInformation;
use App\Models\Enum\TypeInformation;
use App\Models\Information;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InformationFactory extends Factory
{
    protected $model = Information::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'title' => $this->faker->text(),
            'description' => $this->faker->text(),
            'attachment_path' => $this->faker->filePath(),
            'mime_type' => $this->faker->mimeType(),
            'status' => $this->faker->randomElement( StatusInformation::values() ),
            'type' => $this->faker->randomElement( TypeInformation::values() ),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'color' => $this->faker->hexColor(),
            'id_user' => User::inRandomOrder()->first()->id_user,
            'deleted_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
