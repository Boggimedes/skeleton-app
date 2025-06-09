<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'cook_time' => $this->faker->numberBetween(10, 120), // in minutes
            'slug' => $this->faker->slug(),
            'prep_time' => $this->faker->numberBetween(5, 60), // in minutes
            'servings' => $this->faker->numberBetween(1, 10),
            'image_url' => $this->faker->imageUrl(640, 480, 'food', true, 'Recipe'), // Generates a random image URL
        ];
    }
}
