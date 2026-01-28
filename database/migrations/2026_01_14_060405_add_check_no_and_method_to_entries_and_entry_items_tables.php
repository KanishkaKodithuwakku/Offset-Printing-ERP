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
        Schema::table('entries', function (Blueprint $table) {
            $table->string('check_no')->nullable()->after('narration');
            $table->string('method')->nullable()->after('check_no');
        });

        Schema::table('entryitems', function (Blueprint $table) {
            $table->string('check_no')->nullable()->after('reconciliation_date');
            $table->string('method')->nullable()->after('check_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entryitems', function (Blueprint $table) {
            $table->dropColumn(['check_no', 'method']);
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn(['check_no', 'method']);
        });
    }
};
