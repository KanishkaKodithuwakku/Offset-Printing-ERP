<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->integer('partially_added_quantity')->nullable()->after('transferred_quantity');
        });
    }

    public function down()
    {
        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->dropColumn('partially_added_quantity');
        });
    }
}; 