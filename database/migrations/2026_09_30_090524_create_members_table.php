<?php

use App\Models\User;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('member_code', 7)->unique();
            $table->string('first_name', 16);
            $table->string('last_name', 16);
            $table->string('gender', 1);
            $table->date('birth_date');
            $table->string('nik', 16)->unique();
            $table->string('plate_number', 10);
            $table->string('phone_number', 18);
            $table->text('address')->nullable();
            $table->string('emergency_person', 32)->nullable();
            $table->string('emergency_phone_number', 18)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
