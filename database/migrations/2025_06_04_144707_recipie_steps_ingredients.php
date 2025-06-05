<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable(); // e.g., 'vegetable', 'fruit', 'dairy'
            $table->string('measurement_unit')->nullable(); // e.g., 'grams', 'cups', 'tablespoons'
            $table->decimal('serving_quantity', 8, 2)->nullable();
            $table->decimal('calories', 8, 2)->nullable();  // For future calorie calculation
            $table->decimal('carbs', 8, 2)->nullable(); // For future macronutrient calculation
            $table->decimal('protein', 8, 2)->nullable(); // For future macronutrient calculation
            $table->decimal('fat', 8, 2)->nullable(); // For future macronutrient calculation
            $table->timestamps();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained();
            $table->string('measurement_unit')->nullable(); // Measurement unit for the ingredient, e.g., 'grams', 'cups'
            $table->decimal('quantity', 8, 2)->nullable(); // Quantity of the ingredient in the specified measurement unit
            $table->timestamps();
        });

        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->integer('order');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipie_steps');
        Schema::dropIfExists('ingredients');
    }
};
