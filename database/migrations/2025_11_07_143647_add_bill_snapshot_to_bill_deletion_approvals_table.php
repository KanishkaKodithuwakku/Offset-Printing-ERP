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
        Schema::table('bill_deletion_approvals', function (Blueprint $table) {
            $table->date('bill_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('ref_no')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_deletion_approvals', function (Blueprint $table) {
            $table->dropColumn(['bill_date', 'vendor_name', 'ref_no', 'total_amount']);
        });
    }
};
