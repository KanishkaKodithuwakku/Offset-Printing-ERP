<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paid_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postdated_cheque_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();
            
            // Ensure unique combination of cheque and invoice
            $table->unique(['postdated_cheque_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paid_invoices');
    }
};