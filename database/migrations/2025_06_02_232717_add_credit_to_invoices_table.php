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
         Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('credit', 15, 2)->default(0)->after('amount_due');
            $table->string('payment_status')->default('unpaid')->after('credit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['credit', 'payment_status']);
        });
    }
};
