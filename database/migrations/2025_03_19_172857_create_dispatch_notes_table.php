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
        Schema::create('dispatch_notes', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_number')->unique();
            $table->unsignedBigInteger('job_order_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('quantity');
            $table->integer('balance_qty');
            $table->decimal('total_amount', 15, 2);
            $table->timestamp('dispatched_at');
            $table->text('description');
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('job_order_id')->references('id')->on('job_orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_notes');
    }
};
