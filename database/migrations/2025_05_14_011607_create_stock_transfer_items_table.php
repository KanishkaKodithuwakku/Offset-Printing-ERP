<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_transfer_id'); // Link to stock_transfer_notes
            $table->unsignedBigInteger('item_id');         // Item being transferred
            $table->unsignedBigInteger('order_id')->nullable();          // Link to original order (if any)
            $table->unsignedBigInteger('order_item_id')->nullable();     // Link to original order item (if any)

            $table->integer('quantity');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->string('status')->default('pending');

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional)
            $table->foreign('stock_transfer_id')->references('id')->on('stock_transfer_notes')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('order_item_id')->references('id')->on('job_order_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
    }
};
