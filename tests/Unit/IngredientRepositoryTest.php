<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Repositories\IngredientRepository;
use Tests\TestCase;

class IngredientRepositoryTest extends TestCase
{
    public function testGetAllIngredients()
    {
        Ingredient::factory()->count(5)->create();
        $repository = new IngredientRepository();
        $ingredients = $repository->all();
        $this->assertCount(5, $ingredients);
    }

    public function testCreateIngredient()
    {
        $repository = new IngredientRepository();
        $data = ['name' => 'Sugar', 'quantity' => 2];
        $ingredient = $repository->create($data);
        $this->assertEquals('Sugar', $ingredient->name);
    }

    // Add more tests for other methods...
}
