<?php

namespace App\Repositories\Contracts;

interface RecipeRepositoryInterface
{
    public function searchByKeyword(string $word);

    public function searchRecipeByIngredients($query, string $ingredient);

    public function searchRecipeByAuthors($query, string $email);

    public function findByIdOrSlug($idOrSlug);
}
