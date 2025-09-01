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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Customer Name
            $table->string('email')->unique()->nullable(); // Optional: customer email
            $table->string('phone')->nullable(); // Customer phone number
            $table->string('address')->nullable(); // Customer address
            $table->string('city')->nullable(); // Customer city
            $table->string('country')->nullable(); // Customer country
            $table->enum('status', ['active', 'inactive'])->default('active'); // Customer status (active/inactive)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
