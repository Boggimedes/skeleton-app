<?php

namespace App\Repositories\Contracts;

interface IngredientRepositoryInterface
{
    /**
     * Search for ingredients by query.
     */
    public function searchIngredients($query, string $ingredient);
}
