<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RecipeRepositoryInterface;
use App\Repositories\RecipeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RecipeRepositoryInterface::class, RecipeRepository::class);
        $this->app->bind(IngredientRepositoryInterface::class, IngredientRepository::class);
    }

    public function boot()
    {
        //
    }
}