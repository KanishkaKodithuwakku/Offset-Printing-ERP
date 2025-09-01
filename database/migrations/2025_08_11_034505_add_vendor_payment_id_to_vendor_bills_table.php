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
        Schema::table('vendor_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_payment_id')->nullable()->after('created_by');
            $table->foreign('vendor_payment_id')->references('id')->on('vendor_payments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_bills', function (Blueprint $table) {
            $table->dropForeign(['vendor_payment_id']);
            $table->dropColumn('vendor_payment_id');
        });
    }
};
