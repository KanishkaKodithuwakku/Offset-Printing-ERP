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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('order_type');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->enum('status', ['released','invoiced', 'printed', 'delivered', 'cancelled', 'deleted'])->default('released');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('backed_plates_price', 10, 2)->default(0);
            $table->decimal('advance_payment', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'bank', 'credit', 'cheque'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_realization_date')->nullable();
            $table->text('invoice_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
