<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Repositories\RecipeRepository;
use Tests\TestCase;

class RecipeRepositoryTest extends TestCase
{
    public function testGetAllRecipes()
    {
        Recipe::factory()->count(5)->create();
        $repository = new RecipeRepository();
        $recipes = $repository->getAllRecipes();
        $this->assertCount(5, $recipes);
    }

    public function testCreateRecipe()
    {
        $repository = new RecipeRepository();
        $data = ['title' => 'Test Recipe', 'description' => 'Delicious test recipe'];
        $recipe = $repository->createRecipe($data);
        $this->assertEquals('Test Recipe', $recipe->title);
    }

    // Add more tests for other methods...
}
