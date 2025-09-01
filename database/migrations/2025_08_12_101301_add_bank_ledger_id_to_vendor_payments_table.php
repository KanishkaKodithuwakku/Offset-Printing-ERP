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
            // Add bank_ledger_id field without foreign key constraint to avoid errors
            if (!Schema::hasColumn('vendor_payments', 'bank_ledger_id')) {
                $table->unsignedBigInteger('bank_ledger_id')->nullable()->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payments', 'bank_ledger_id')) {
                $table->dropColumn('bank_ledger_id');
            }
        });
    }
};
