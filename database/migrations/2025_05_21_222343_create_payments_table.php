<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('method', 10); // e.g., CA (cash), CH (cheque)
            $table->string('check_number')->nullable();
            $table->string('memo')->nullable();
            // $table->foreignId('entry_id')->nullable()->constrained()->onDelete('set null');
            $table->bigInteger('entry_id')->nullable();
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
