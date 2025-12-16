<?php

namespace Database\Factories;

use App\Models\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CalendarEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = fake()->dateTimeBetween($start, $start->format('Y-m-d H:i:s') . ' +1 week');

        return [
            'id' => $this->faker->uuid(),
            'title' => fake()->sentence(),
            'start' => $start,
            'end' => $end,
            'description' => fake()->paragraph(),
            'color' => fake()->hexColor(),
            'all_day' => fake()->boolean(),
        ];
    }
}
