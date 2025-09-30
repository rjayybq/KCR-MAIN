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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Ingredient name
            $table->decimal('stock', 10, 2)->default(0); // Stock qty (allows int & decimal)
            $table->string('unit')->default('pcs');  // Unit (pcs, kg, g, L, etc.)
            $table->boolean('is_active')->default(true); // Active status (true/false)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
