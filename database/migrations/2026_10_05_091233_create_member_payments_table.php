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
        Schema::create('member_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('event_due_id');

            $table->float('payed')->default(0);

            $table->date('date_payed_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('event_due_id')->references('id')->on('event_dues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};
