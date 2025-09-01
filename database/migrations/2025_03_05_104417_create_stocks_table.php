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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brands_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('items_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('vendors')->nullOnDelete(); // Supplier/vendor ID
            $table->foreignId('p_id')->nullable()->constrained('purchases')->nullOnDelete(); // Purchase ID (if applicable)
            $table->foreignId('f_id')->nullable()->constrained('factories')->nullOnDelete(); // Factory ID
            $table->foreignId('inventory_account')->nullable()->constrained('accounts')->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->decimal('mrp', 10, 2)->nullable(); // Maximum retail price
            $table->decimal('purchase_price', 10, 2)->nullable(); // Purchase price
            $table->decimal('purchase_tax', 10, 2)->nullable(); // Purchase tax
            $table->decimal('sales_price', 10, 2)->nullable();
            $table->foreignId('sales_tax')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('total_purchase_price', 10, 2)->nullable(); // Total purchase price (purchase_price * qty)
            $table->enum('payment_type', ['cash', 'card', 'cheque', 'bank'])->default('cash');
            $table->date('purchase_date')->nullable(); // Date of purchase
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('mpn')->nullable();
            $table->string('isbn')->nullable();
            $table->string('upc')->nullable();
            $table->string('ean')->nullable();
            $table->date('effective_date'); // Date when the stock will be available
            $table->string('sku_code')->nullable(false);
            $table->boolean('online')->default(1); // 1 = Online, 0 = Offline
            $table->timestamps();
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
