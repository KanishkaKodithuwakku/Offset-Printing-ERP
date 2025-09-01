<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('grn_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grn_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('brands_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->enum('order_type', ['PO', 'MR'])->default('PO');
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->string('batch')->nullable();
            $table->string('lot')->nullable();
            $table->string('color')->nullable();
            $table->string('sku_code')->unique()->nullable(false);
            $table->integer('order_quantity');
            $table->integer('quantity');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('mrp', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
            $table->foreign('grn_id')->references('id')->on('grns')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('brands_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grn_items');
    }
};
