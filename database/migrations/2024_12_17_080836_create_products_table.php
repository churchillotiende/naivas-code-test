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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Product name
            $table->string('sku')->unique(); // Unique SKU
            $table->text('description')->nullable(); // Product description
            $table->decimal('price', 10, 2)->nullable(); // Product price
            $table->integer('quantity')->default(0); // Stock quantity
            $table->string('image')->nullable(); // Product image path
            $table->enum('status', ['active', 'inactive'])->default('active'); // Product status
            $table->foreignId('category_id')->default(1)->constrained()->cascadeOnDelete();
            $table->timestamps(); // Created_at and updated_at timestamps
            $table->softDeletes();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
