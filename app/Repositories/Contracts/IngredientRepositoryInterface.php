<?php

namespace App\Repositories;

interface IngredientRepositoryInterface
{
    /**
     * Search for ingredients by query.
     *
     * @param string $query
     * @return mixed
     */
    public function searchIngredients(string $query);


}