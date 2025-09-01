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
            // Drop existing columns that are not needed (only if they exist)
            $columnsToDrop = ['memo', 'branch_id', 'entry_id'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('vendor_payments', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add new columns
            if (!Schema::hasColumn('vendor_payments', 'receipt_number')) {
                $table->string('receipt_number')->after('payment_date');
            }

            if (!Schema::hasColumn('vendor_payments', 'check_date')) {
                $table->date('check_date')->nullable()->after('check_number');
            }

            if (!Schema::hasColumn('vendor_payments', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->after('check_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            // Drop new columns
            $columnsToDrop = ['receipt_number', 'check_date', 'total_amount'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('vendor_payments', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add back original columns
            if (!Schema::hasColumn('vendor_payments', 'memo')) {
                $table->text('memo')->nullable()->after('check_number');
            }

            if (!Schema::hasColumn('vendor_payments', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('memo');
            }

            if (!Schema::hasColumn('vendor_payments', 'entry_id')) {
                $table->unsignedBigInteger('entry_id')->nullable()->after('branch_id');
            }
        });
    }
};
