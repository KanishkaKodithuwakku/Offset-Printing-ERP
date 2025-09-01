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
        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->id();     
            $table->foreignId('vendor_id')->constrained('suppliers')->onDelete('cascade');
            $table->date('date');
            $table->string('ref_no')->nullable();
            $table->date('bill_due_date');
            $table->string('terms')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_due', 15, 2);
            $table->string('payment_type')->nullable();     // ← just declare it
            $table->string('cheque_number')->nullable();    // ← no ->after()
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_bills');
    }
};
