<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->string('grn_code')->unique();
            $table->enum('order_type', ['PO', 'MR'])->default('PO');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('supplier_id')->default(1);
            $table->date('grn_date');
            $table->date('eff_date')->default(now());
            $table->decimal('total_amount', 15, 2);
            $table->text('remark')->nullable();
            $table->date('delivery_date');
            $table->string('delivery_location')->nullable();
            $table->text('delivery_remark')->nullable();
            $table->string('grn_type')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'completed'
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grns');
    }
};
