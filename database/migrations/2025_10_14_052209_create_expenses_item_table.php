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
        Schema::create('expenses_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('expense_id');
            $table->decimal('expense_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('expense_id')->references('id')->on('other_expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_item');
    }
};
