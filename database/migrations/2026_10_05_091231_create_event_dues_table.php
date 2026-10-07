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
        Schema::create('event_dues', function (Blueprint $table) {
            $table->id();

            $table->string('name', 64);
            $table->text('description')->nullable();

            $table->float('minimum_pay')->default(0);

            $table->dateTime('published_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_dues');
    }
};
