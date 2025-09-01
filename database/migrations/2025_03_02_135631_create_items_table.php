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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brands_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('item_code')->unique(); // Ensuring uniqueness
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->text('item_short_description')->nullable();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->string('uom')->nullable(); // Unit of Measure
            $table->decimal('discount', 10, 2)->nullable();
            $table->enum('item_type', ['RW', 'FG'])->default('FG');// RW = Raw Material, FG = Finished Goods
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('returnable')->default(false);
            $table->json('dimensions')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('mpn')->nullable();
            $table->string('isbn')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable();
            $table->decimal('sales_price', 10, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->foreignId('sales_account')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('purchase_account')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('sales_tax')->nullable()->constrained('taxes')->nullOnDelete();
            $table->foreignId('purchase_tax')->nullable()->constrained('taxes')->nullOnDelete();
            $table->foreignId('preferred_vendor')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('inventory_account')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
