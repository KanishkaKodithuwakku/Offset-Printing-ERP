<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_credit_id');
            $table->unsignedBigInteger('invoice_id')->nullable(); // or payment_id if needed
            $table->decimal('amount_applied', 15, 2);
            $table->date('applied_date');
            $table->timestamps();

            $table->foreign('customer_credit_id')->references('id')->on('customer_credits')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_applications');
    }
};
