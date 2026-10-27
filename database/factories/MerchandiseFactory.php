<?php

namespace Database\Factories;

use App\Models\Merchandise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Merchandise>
 */
class MerchandiseFactory extends Factory
{
    protected $model = Merchandise::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(25000, 250000),
            'stock' => fake()->numberBetween(5, 50),
            'path_image' => null,
            'is_available' => true,
        ];
    }
}
