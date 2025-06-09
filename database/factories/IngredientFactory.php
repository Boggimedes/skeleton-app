<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'serving_quantity' => $this->faker->randomFloat(2, 0, 100),
            'measurement_unit' => $this->faker->randomElement(['grams', 'liters', 'pieces']),
        ];
    }
}
