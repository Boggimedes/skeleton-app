<?php
namespace Tests\Unit;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testIngredientFactoryCreatesValidData()
    {
        $ingredient = Ingredient::factory()->create();

        $this->assertNotEmpty($ingredient->name);
        $this->assertDatabaseHas('ingredients', ['name' => $ingredient->name]);
    }
}