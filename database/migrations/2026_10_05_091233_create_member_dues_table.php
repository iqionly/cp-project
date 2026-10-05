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
        Schema::create('member_dues', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_due_id');
            $table->unsignedBigInteger('member_id');

            $table->unsignedInteger('dues_payed')->default(0);

            $table->date('date_payed_at');
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            $table->foreign('event_due_id')->references('id')->on('event_due_id');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_dues');
    }
};
