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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('first_name', 16)->nullable()->change();
            $table->string('last_name', 16)->nullable()->change();
            $table->string('gender', 1)->nullable()->change();
            $table->date('birth_date')->nullable()->change();
            $table->string('nik', 16)->nullable()->change();
            $table->string('plate_number', 10)->nullable()->change();
            $table->string('phone_number', 18)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('first_name', 16)->nullable(false)->change();
            $table->string('last_name', 16)->nullable(false)->change();
            $table->string('gender', 1)->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
            $table->string('nik', 16)->nullable(false)->change();
            $table->string('plate_number', 10)->nullable(false)->change();
            $table->string('phone_number', 18)->nullable(false)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
