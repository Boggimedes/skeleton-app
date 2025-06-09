<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Contracts\IngredientRepositoryInterface;

class IngredientRepository extends BaseRepository implements IngredientRepositoryInterface
{
    /**
     * RecipeRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Ingredient::class);
    }

    public function searchIngredients($query, string $ingredient)
    {
        $ingredientQuery = $query->where('name', 'like', '%'.$ingredient.'%');
        $ingredients = $ingredientQuery->get();
        // If no (or one single name that is not an exact match) names were found with the initial fuzzy search, try levenshtein
        if (($ingredients->isEmpty() && strlen($ingredient) > 4) || ($ingredients->count() === 1 && $ingredients[0]->name !== $ingredient)) {
            $levNum = 1;
            if (strlen($ingredient) > 5) {
                $levNum = 2;
            }
            if (strlen($ingredient) > 10) {
                $levNum = 3;
            }

            // Static DB name 'skeleton_app' is used here for expediency. Long term Levenshtein needs to be added to the test database
            return $query->whereRaw("skeleton_app.levenshtein(`ingredients`.name, '$ingredient')<={$levNum}")
            ->orWhere('ingredients.name', 'LIKE', "%{$ingredient}%")->orderByRaw("skeleton_app.levenshtein(`ingredients`.name, '$ingredient') ASC");
        }

        return $ingredientQuery;
    }
}
