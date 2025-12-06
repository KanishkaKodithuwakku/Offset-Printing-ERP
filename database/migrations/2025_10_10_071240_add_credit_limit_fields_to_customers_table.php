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
        Schema::table('customers', function (Blueprint $table) {
            // Credit Limit 1 fields
            $table->integer('credit_limit_1_days')->nullable()->after('status')->comment('Credit days for limit 1');
            $table->decimal('credit_limit_1_amount', 15, 2)->nullable()->after('credit_limit_1_days')->comment('Credit amount for limit 1');

            // Credit Limit 2 fields
            $table->integer('credit_limit_2_days')->nullable()->after('credit_limit_1_amount')->comment('Credit days for limit 2');
            $table->decimal('credit_limit_2_amount', 15, 2)->nullable()->after('credit_limit_2_days')->comment('Credit amount for limit 2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'credit_limit_1_days',
                'credit_limit_1_amount',
                'credit_limit_2_days',
                'credit_limit_2_amount'
            ]);
        });
    }
};
