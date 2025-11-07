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
            $table->date('bill_date')->nullable()->after('vendor_bill_id');
            $table->string('vendor_name')->nullable()->after('bill_date');
            $table->string('ref_no')->nullable()->after('vendor_name');
            $table->decimal('total_amount', 15, 2)->nullable()->after('ref_no');
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
