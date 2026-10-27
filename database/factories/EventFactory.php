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
        static $lastStart = 'now';

        $start = $this->faker->dateTimeBetween();
        $end = $this->faker->dateTimeBetween($start);

        if(rand(0, 10) > 7) {
            $starting = $lastStart == 'now' ? $lastStart : "now +{$lastStart} months";
            $ending = $lastStart == 'now' ? $lastStart : "now +" . $lastStart + 1 . "months";
            $start = $this->faker->dateTimeBetween($starting, $ending);
            $end = $this->faker->dateTimeBetween($start->format('Y-m-d H:i:s') . ' +1 days', $start->format("Y-m-d H:i:s") . ' +2 days');

            if($lastStart == 'now') {
                $lastStart = 1;
            } else {
                $lastStart++;
            }
        }
        return [
            'location' => $this->faker->city(),
            'start_at' => $start->format('Y-m-d H:i:s'),
            'end_at' => $end->format('Y-m-d H:i:s'),
            'max_attendant' => $this->faker->randomNumber(2),
            'min_attendant' => $this->faker->randomNumber(1),
        ];
    }
}
