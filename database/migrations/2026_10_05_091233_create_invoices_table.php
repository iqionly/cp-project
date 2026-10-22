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
        Schema::create('invoice_journals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('code', 16)->unique();
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->string('invoice_number', 40)->unique();
            $table->date('period_start');
            $table->date('issued_at');
            $table->date('due_at');
            $table->string('status', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Satu invoice per member per bulan.
            $table->unique(['member_id', 'period_start']);
            $table->index(['status', 'due_at']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->foreignId('invoice_journal_id')->constrained('invoice_journals')->restrictOnDelete();
            $table->string('description', 255);
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->index(['invoice_id', 'invoice_journal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_journals');
        Schema::dropIfExists('invoices');
    }
};
