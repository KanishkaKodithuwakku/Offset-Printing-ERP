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
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->after('supplier_id')
                ->constrained('branches') // assumes 'id' as FK
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->after('branch_id')
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
