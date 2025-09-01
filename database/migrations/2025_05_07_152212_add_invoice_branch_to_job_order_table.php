<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->foreignId('invoice_branch')
                ->nullable()
                ->after('branch_id')
                ->constrained('branches')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['invoice_branch']);
            $table->dropColumn('invoice_branch');
        });
    }
};
