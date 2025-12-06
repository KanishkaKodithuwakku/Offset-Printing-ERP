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
        Schema::create('bill_deletion_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_bill_id')
                ->nullable()
                ->constrained('vendor_bills')
                ->onDelete('set null');

            $table->date('bill_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('ref_no')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->onDelete('cascade');

            $table->text('reason')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_deletion_approvals');
    }
};
