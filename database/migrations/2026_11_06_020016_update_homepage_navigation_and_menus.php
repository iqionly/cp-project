<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename Profile menu & post to Sejarah Club
        $profileMenu = DB::table('menu_posts')->where('slug', 'profile')->first();
        if ($profileMenu) {
            DB::table('menu_posts')
                ->where('slug', 'profile')
                ->update([
                    'menu_title' => 'Sejarah Club',
                    'default' => 40,
                ]);

            if ($profileMenu->post_id) {
                DB::table('posts')
                    ->where('id', $profileMenu->post_id)
                    ->update(['title' => 'Sejarah Club']);
            }
        }

        // 2. Remove Struktur from menu_posts so its standalone page is removed from navbar
        DB::table('menu_posts')->where('slug', 'struktur')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore Profile menu title
        $profileMenu = DB::table('menu_posts')->where('slug', 'profile')->first();
        if ($profileMenu) {
            DB::table('menu_posts')
                ->where('slug', 'profile')
                ->update([
                    'menu_title' => 'Profile',
                    'default' => 100,
                ]);

            if ($profileMenu->post_id) {
                DB::table('posts')
                    ->where('id', $profileMenu->post_id)
                    ->update(['title' => 'Profile']);
            }
        }

        // Re-create Struktur menu if post exists
        $strukturPost = DB::table('posts')
            ->where('title', 'like', '%Struktur%')
            ->first();

        if ($strukturPost) {
            DB::table('menu_posts')->insert([
                'post_id' => $strukturPost->id,
                'slug' => 'struktur',
                'default' => 200,
                'menu_title' => 'Struktur',
                'icons' => null,
            ]);
        }
    }
};
