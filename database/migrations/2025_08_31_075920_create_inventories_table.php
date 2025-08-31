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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('ProductName'); // product name
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null'); 
            $table->decimal('weight', 8, 2)->default(0); // e.g. 1.5
            $table->string('unit', 50)->default('kg'); // e.g. kg, g, lbs
            $table->integer('stock')->default(0); // stock quantity
            $table->decimal('price', 10, 2)->default(0); // e.g. 199.99
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
