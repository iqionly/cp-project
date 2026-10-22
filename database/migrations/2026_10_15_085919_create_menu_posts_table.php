<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_posts', function(Blueprint $table) {
            $table->unsignedBigInteger('post_id')->primary();
            $table->string('slug')->nullable();
            $table->boolean('default')->default(false);
            $table->string('menu_title')->nullable();
            $table->string('icons')->nullable();

            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('menu_posts');
    }
};
