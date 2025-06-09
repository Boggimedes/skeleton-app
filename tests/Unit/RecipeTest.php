<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateRecipe()
    {
        $data = [
            'title' => 'New Recipe',
            'description' => 'A delicious new recipe.',
            'ingredients' => [
                ['name' => 'Ingredient 1', 'quantity' => '1', 'unit' => 'Cup'],
                ['name' => 'Ingredient 2', 'quantity' => '2', 'unit' => 'Tablespoons'],
            ],
            'steps' => [
                ['description' => 'Step 1: Do this', 'order' => 1],
                ['description' => 'Step 2: Do that', 'order' => 2],
            ],
        ];

        $response = $this->postJson('/api/recipe', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('recipes', ['title' => 'New Recipe']);
        $this->assertDatabaseHas('ingredients', ['name' => 'Ingredient 2']);
        $this->assertDatabaseHas('recipe_steps', ['description' => 'Step 1: Do this']);
    }

    public function testFetchRecipes()
    {
        $response = $this->getJson('/api/recipes');

        $response->assertStatus(200);
        $response->assertJsonCount(7);  // Assuming there are 7 recipes in the database initially

        Recipe::factory(3)->create();

        $response = $this->getJson('/api/recipes');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    public function testRecipeHasIngredients()
    {
        $recipe = Recipe::factory()->create();
        $recipeIngredient = RecipeIngredient::factory()->state(['recipe_id' => $recipe->id])->create();
        $this->assertTrue($recipe->ingredients->contains($recipeIngredient->ingredient));
    }

    public function testRecipeAttributes()
    {
        $recipe = Recipe::factory()->create([
            'title' => 'Test Recipe',
            'description' => 'This is a test recipe.',
        ]);

        $this->assertEquals('Test Recipe', $recipe->title);
    }

    public function testDeleteRecipe()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson("/api/recipe/{$recipe->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function testSearchRecipesByKeyword()
    {
        Recipe::factory()->create(['title' => 'Chicken Curry']);
        Recipe::factory()->create(['title' => 'Beef Stew']);
        Recipe::factory()->create(['title' => 'Vegetable Soup']);

        $response = $this->getJson('/api/recipes/search?keyword=Chicken');
        \Log::info($response->getContent());
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['title' => 'Chicken Curry']);
    }
}
