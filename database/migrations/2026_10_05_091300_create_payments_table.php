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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')
                ->constrained('members')->restrictOnDelete();
            $table->string('payment_number', 40)->unique();
            $table->dateTime('paid_at')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('method', 20);
            $table->string('reference_number', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status', 'paid_at']);
            $table->unique(['method', 'reference_number']);
        });

        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->constrained('payments')->restrictOnDelete();
            $table->foreignId('invoice_id')
                ->constrained('invoices')->restrictOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->unique(['payment_id', 'invoice_id']);
            $table->index(['invoice_id', 'payment_id']);
        });

        Schema::create('invoice_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                ->constrained('invoices')->restrictOnDelete();
            $table->string('reminder_type', 30);
            $table->dateTime('scheduled_at');
            $table->dateTime('sent_at')->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique([
                'invoice_id', 'reminder_type', 'scheduled_at'
            ], 'invoice_reminder_schedule_unique');

            $table->index(['status', 'scheduled_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_reminders');
        Schema::dropIfExists('payment_allocations');
        Schema::dropIfExists('payments');
    }
};
