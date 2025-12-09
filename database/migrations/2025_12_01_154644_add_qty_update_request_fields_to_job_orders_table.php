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
        Schema::table('job_orders', function (Blueprint $table) {
            $table->boolean('qty_update_requested')->default(false)->after('status');
            $table->unsignedBigInteger('qty_update_requested_by')->nullable()->after('qty_update_requested');
            $table->timestamp('qty_update_requested_at')->nullable()->after('qty_update_requested_by');
            
            $table->foreign('qty_update_requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['qty_update_requested_by']);
            $table->dropColumn(['qty_update_requested', 'qty_update_requested_by', 'qty_update_requested_at']);
        });
    }
};
