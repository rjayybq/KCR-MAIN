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
        Schema::create('ingredient_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out']); // track kung stock in or out
            $table->decimal('movement_qty', 10, 2);  // magkano in or out
            $table->date('date');                // kailan nangyari
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_stock');
    }
};
