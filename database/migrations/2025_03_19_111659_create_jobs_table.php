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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->string('customer_po_number');
            $table->timestamp('date_created')->useCurrent();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('description');
            $table->text('special_instruction')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->unsignedBigInteger('job_done_by')->nullable();
            $table->unsignedBigInteger('job_checked_by')->nullable();
            $table->timestamp('delivery_date');
            $table->string('status')->default('pending');
            $table->boolean('plate_backing')->default(false);
            $table->integer('backing_qty')->nullable();
            $table->integer('print_count')->default(0);
            $table->integer('reorder_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relationships
            $table->foreign('assign_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_done_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_checked_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
