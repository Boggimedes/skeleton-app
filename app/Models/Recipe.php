<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'category_id',
        'image_url',
        'prep_time',
        'cook_time',
        'servings',
        'slug', // Unique slug for SEO-friendly URLs
    ];

    /**
     * Get the user that owns the recipe.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ingredients for the recipe.
     */
    public function ingredients()
    {
        return $this->hasManyThrough(Ingredient::class, RecipeIngredient::class, 'recipe_id', 'id', 'id', 'ingredient_id');
    }

    /**
     * Get the steps for the recipe.
     */
    public function recipeSteps()
    {
        return $this->hasMany(RecipeStep::class);
    }

    /**
     * Get the recipe_ingredients for the recipe.
     */
    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}
