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
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('member_payment_id');

            $table->string('filename');
            $table->text('path');
            $table->text('description')->nullable();

            $table->timestamp('approved_at');
            $table->timestamp('created_at');

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('member_payment_id')->references('id')->on('member_payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
