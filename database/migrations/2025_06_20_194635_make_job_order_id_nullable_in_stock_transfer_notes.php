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
        Schema::table('stock_transfer_notes', function (Blueprint $table) {
            // Modify the job_order_id column to make it nullable
            $table->unsignedBigInteger('job_order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transfer_notes', function (Blueprint $table) {
            // Rollback the change (make job_order_id not nullable again)
            $table->unsignedBigInteger('job_order_id')->nullable(false)->change();
        });
    }
};
