<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            // Uncomment if you want foreign key constraint
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });

        Schema::table('entryitems', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            // Uncomment if you want foreign key constraint
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entryitems', function (Blueprint $table) {
            // $table->dropForeign(['branch_id']); // if foreign key added
            $table->dropColumn('branch_id');
        });

        Schema::table('entries', function (Blueprint $table) {
            // $table->dropForeign(['branch_id']); // if foreign key added
            $table->dropColumn('branch_id');
        });
    }
};
