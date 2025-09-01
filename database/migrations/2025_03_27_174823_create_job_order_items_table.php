<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_items', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('order_id'); // Foreign key to the job_orders table
            $table->unsignedBigInteger('item_id'); // Foreign key to the items table
            $table->integer('quantity'); // Quantity of the item
            $table->decimal('price', 10, 2); // Price of the item
            $table->decimal('total', 10, 2); // Total price for the quantity (price * quantity)
            $table->string('status')->default('printing'); // Total price for the quantity (price * quantity)
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_items');
    }
}
