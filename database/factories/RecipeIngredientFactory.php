<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeIngredient>
 */
class RecipeIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => \App\Models\Recipe::factory(),
            'ingredient_id' => \App\Models\Ingredient::factory(),
            'quantity' => $this->faker->randomFloat(2, 0.1, 10), // Random quantity between 0.1 and 10
            'unit' => $this->faker->randomElement(['grams', 'ml', 'cups', 'tablespoons', 'teaspoons']),
        ];
    }
}
