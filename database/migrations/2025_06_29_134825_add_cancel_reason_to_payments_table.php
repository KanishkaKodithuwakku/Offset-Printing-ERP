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
        Schema::table('payments', function (Blueprint $table) {
            // use text if you expect longer reasons; string() is OK for shorter
            $table->text('cancel_reason')
                  ->nullable()
                  ->after('memo'); 
            // replace 'some_existing_column' with the column you want it after, or omit ->after(...) 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('cancel_reason');
        });
    }
};
