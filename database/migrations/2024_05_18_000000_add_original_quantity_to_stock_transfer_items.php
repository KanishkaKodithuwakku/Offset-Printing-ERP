<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->integer('original_quantity')->nullable()->after('quantity');
        });
        // Set original_quantity = quantity for existing records
        DB::statement('UPDATE stock_transfer_items SET original_quantity = quantity');
    }

    public function down()
    {
        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->dropColumn('original_quantity');
        });
    }
}; 