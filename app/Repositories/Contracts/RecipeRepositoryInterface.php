<?php

namespace App\Repositories;

interface RecipeRepositoryInterface
{
    public function searchByKeyword(string $word);

    public function searchByIngredient(string $ingredient);

    public function searchByCategory(string $category);
}