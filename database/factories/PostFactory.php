<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
#[UseModel(Post::class)]
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviewed = null;

        return [
            'title' => substr(fake()->paragraph, 0, 45),
            'description' => fake()->text(400),
            'reviewed_at' => rand(0, 10) > 7 ? $reviewed = date('Y-m-d') : null,
            'published_at' => rand(0, 10) > 7 && $reviewed ? date('Y-m-d') : null,
        ];
    }

}
