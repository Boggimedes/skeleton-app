<?php

namespace App\Repositories;

use App\Models\Ingredient;

class IngredientRepository implements IngredientRepositoryInterface
{
    public function getAllIngredients()
    {
        return Ingredient::all();
    }

    public function getIngredientById($id)
    {
        return Ingredient::findOrFail($id);
    }

    public function createIngredient(array $data)
    {
        return Ingredient::create($data);
    }

    public function updateIngredient($id, array $data)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($data);
        return $ingredient;
    }

    public function deleteIngredient($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        return $ingredient;
    }

    public function searchIngredients($query)
    {
        $ingredients = Ingredient::where('name', 'like', '%' . $query . '%')->get();
        // If no (or one single name that is not an exact match) names were found with the initial fuzzy search, try levenshtein
        if (($ingredients->isEmpty() && strlen($query) > 4) || ($ingredients->count() === 1 && $ingredients[0]->name !== $query)) {
            $levIngred = Ingredient::fromQuery("SELECT * FROM ingredients WHERE levenshtein(left(`ingredients`.`name`,length('$query')), '$query')<=3 ORDER BY levenshtein(name, '$query'), name");
            $ingredients = $ingredients->merge($levIngred);
        }
        return $ingredients;
    }
}