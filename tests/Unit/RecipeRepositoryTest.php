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
        $recipes = $repository->all();
        $this->assertCount(5, $recipes);
    }

    public function testCreateRecipe()
    {
        $repository = new RecipeRepository();
        $data = ['title' => 'Test Recipe', 'description' => 'Delicious test recipe'];
        $recipe = $repository->create($data);
        $this->assertEquals('Test Recipe', $recipe->title);
    }

    public function testUpdateRecipe()
    {
        $repository = new RecipeRepository();
        $recipe = Recipe::factory()->create(['title' => 'Old Title']);
        $updatedRecipe = $repository->update($recipe, ['title' => 'New Title']);

        $this->assertEquals('New Title', $updatedRecipe->title);
    }

    // Add more tests for other methods...
}
