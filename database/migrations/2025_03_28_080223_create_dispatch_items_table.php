<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_items', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('item_id'); // Foreign key to the items table
            $table->unsignedBigInteger('order_id'); // Foreign key to the job_orders table
            $table->unsignedBigInteger('dispatch_id'); // Foreign key to the job_orders table
            $table->unsignedBigInteger('job_order_item_id'); // Foreign key to the job_order_items table
            $table->unsignedBigInteger('user_id'); // Foreign key to the users table
            $table->integer('quantity'); // Quantity of the item to be dispatched
            $table->decimal('total_amount', 10, 2); // Total amount for the dispatch (price * quantity)
            $table->string('status')->default('pending'); // Status of the dispatch (default is 'pending')
            $table->unsignedBigInteger('branch_assigned')->nullable(); // Assigned branch (default to 1)
            $table->unsignedBigInteger('branch_done')->nullable(); // Done branch (default to 1)
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('dispatch_id')->references('id')->on('dispatch_notes')->onDelete('cascade');
            $table->foreign('job_order_item_id')->references('id')->on('job_order_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_items');
    }
}
