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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name'); // Name of the account (e.g., 'Sales Account', 'Purchase Account')
            $table->string('account_code')->unique(); // A unique code for the account (e.g., 'SA123')
            $table->enum('account_type', ['asset', 'liability', 'equity', 'income', 'expense']) // Type of account
                  ->default('asset');
            $table->decimal('opening_balance', 15, 2)->default(0.00); // Current balance in the account
            $table->text('description')->nullable(); // Description of the account
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // Foreign key for user who created the account
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // Foreign key for user who last updated the account
            $table->timestamps(); // Timestamps for created_at and updated_at
            $table->softDeletes(); // Soft delete column (deleted_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
