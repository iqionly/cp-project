<?php

namespace Database\Seeders;

use App\Models\MasterData\Member;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Member::factory(100)->create();
        Post::factory(200)->for(User::query()->find(1))->create();
    }
}
