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
        Schema::table('vendor_payments', function (Blueprint $table) {
            // Update payment_method enum values to match the code
            $table->enum('payment_method', ['CA', 'CH'])->default('CA')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('payment_method', ['cash', 'bank', 'cheque', 'credit'])->default('cash')->change();
        });
    }
};
