<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Contracts\RecipeRepositoryInterface;
use Illuminate\Support\Str;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * Get all recipes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Recipe::all();
    }

    /**
     * Find a recipe by its ID.
     *
     * @return Recipe|null
     */
    public function findById(int $id)
    {
        return Recipe::find($id);
    }

    /**
     * Create a new recipe.
     *
     * @return Recipe
     */
    public function create(array $data)
    {
        $data['slug'] = $this->createUniqueSlugStr($data['title']);

        return Recipe::create($data);
    }

    /**
     * Create a new unique slug.
     *
     * @param string $title
     *
     * @return string
     */
    private function createUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug.'-'.$count++;
        }

        return $slug;
    }

    /**
     * Check for existing slug string for a recipe.
     *
     * @param string $slug
     *
     * @return bool
     */
    private function slugExists($slug)
    {
        return Recipe::where('slug', $slug)->exists();
    }

    /**
     * Update an existing recipe.
     *
     * @return bool
     */
    public function update(int $id, array $data)
    {
        $recipe = $this->findById($id);

        if ($recipe) {
            return $recipe->update($data);
        }

        return false;
    }

    /**
     * Delete a recipe by its ID.
     *
     * @return bool|null
     */
    public function delete(int $id)
    {
        $recipe = $this->findById($id);

        if ($recipe) {
            return $recipe->delete();
        }

        return false;
    }
}
