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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade'); // Reference to sales
            $table->string('table_id');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // Reference to accounts
            $table->decimal('debit', 10, 2)->default(0); // Debit amount
            $table->decimal('credit', 10, 2)->default(0); // Credit amount
            $table->enum('transaction_type', ['debit', 'credit']); // Type of transaction (debit or credit)
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
