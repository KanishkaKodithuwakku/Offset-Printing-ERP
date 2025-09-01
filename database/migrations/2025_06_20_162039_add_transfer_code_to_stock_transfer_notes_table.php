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
            // Add transfer_code column
            $table->string('transfer_code')->unique()->nullable();  // Unique and nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transfer_notes', function (Blueprint $table) {
            // Drop the transfer_code column
            $table->dropColumn('transfer_code');
        });
    }
};
