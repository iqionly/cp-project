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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('post_id');

            $table->string('location');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->unsignedInteger('max_attendant');
            $table->unsignedInteger('min_attendant');

            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
