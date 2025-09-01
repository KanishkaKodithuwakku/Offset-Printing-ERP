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
        Schema::create('vendor_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_payment_id');
            $table->unsignedBigInteger('vendor_bill_id');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();

            $table->foreign('vendor_payment_id')->references('id')->on('vendor_payments')->onDelete('cascade');
            $table->foreign('vendor_bill_id')->references('id')->on('vendor_bills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payment_details');
    }
};
