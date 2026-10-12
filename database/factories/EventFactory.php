<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween();
        $end = $this->faker->dateTimeBetween($start);
        return [
            'location' => $this->faker->city(),
            'start_at' => $start,
            'end_at' => $end,
            'max_attendant' => $this->faker->randomNumber(2),
            'min_attendant' => $this->faker->randomNumber(1),
        ];
    }
}
