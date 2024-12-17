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
            $table->string('first_name'); // Customer's first name
            $table->string('last_name'); // Customer's last name
            $table->string('email')->unique(); // Unique email address
            $table->string('phone', 20)->nullable(); // Phone number
            $table->text('address')->nullable(); // Address
            $table->string('city', 100)->nullable(); // City
            $table->string('state', 100)->nullable(); // State
            $table->string('postal_code', 20)->nullable(); // Postal code
            $table->string('country', 100)->nullable(); // Country
            $table->enum('status', ['active', 'inactive'])->default('active'); // Account status
            $table->timestamps();
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
