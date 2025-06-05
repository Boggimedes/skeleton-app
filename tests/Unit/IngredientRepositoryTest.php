<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ingredient;
use App\Repositories\IngredientRepository;

class IngredientRepositoryTest extends TestCase
{
    public function testGetAllIngredients()
    {
        Ingredient::factory()->count(5)->create();
        $repository = new IngredientRepository();
        $ingredients = $repository->getAllIngredients();
        $this->assertCount(5, $ingredients);
    }

    public function testCreateIngredient()
    {
        $repository = new IngredientRepository();
        $data = ['name' => 'Sugar', 'quantity' => 2];
        $ingredient = $repository->createIngredient($data);
        $this->assertEquals('Sugar', $ingredient->name);
    }

    // Add more tests for other methods...
}