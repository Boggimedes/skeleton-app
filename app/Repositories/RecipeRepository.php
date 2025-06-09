<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Contracts\RecipeRepositoryInterface;
use Illuminate\Support\Str;

class RecipeRepository extends BaseRepository implements RecipeRepositoryInterface
{
    /**
     * @var IngredientRepository
     */
    private $ingredientRepository;

    /**
     * RecipeRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Recipe::class);
        $this->ingredientRepository = new IngredientRepository();
    }

    /**
     * Search recipes by keyword in the title or description.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchByKeyword(string $word)
    {
        \Log::info("Searching recipes with keyword: {$word}");
        if (empty($word)) {
            return Recipe::query();
        }

        return Recipe::where(function ($query) use ($word) {
            $query->where('title', 'LIKE', "%{$word}%")
                ->orWhere('description', 'LIKE', "%{$word}%")
                ->orWhereHas('ingredients', function ($query) use ($word) {
                    $query->where('name', 'LIKE', "%{$word}%");
                })
                ->orWhereHas('recipeSteps', function ($query) use ($word) {
                    $query->where('description', 'LIKE', "%{$word}%");
                });
        });
    }

    /**
     * Search recipes by ingredient.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchRecipeByIngredients($query, string $ingredient)
    {
        return $query->whereHas('ingredients', function ($query) use ($ingredient) {
            $this->ingredientRepository->searchIngredients($query, $ingredient);
        });
    }

    /**
     * Search recipes by category.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchRecipeByAuthors($query, string $email)
    {
        $author = User::where('email', $email)->first();
        if (!$author) {
            return $query;
        }

        return $query->where('user_id', $author->id);
    }

    /**
     * Create a new recipe.
     *
     * @return Recipe
     */
    public function create(array $data)
    {
        $data['slug'] = $this->createUniqueSlugStr($data['title']);
        $steps = $data['steps'] ?? [];
        unset($data['steps']);
        $ingredients = $data['ingredients'] ?? [];
        unset($data['ingredients']);
        $recipe = Recipe::create($data);
        \Log::info('Creating recipe with data: '.json_encode($ingredients));
        \Log::info('Recipe created with ID: '.json_encode($steps));
        if (isset($ingredients)) {
            $this->saveRecipeIngredients($recipe, $ingredients);
        }
        if (isset($steps)) {
            $this->saveRecipeSteps($recipe, $steps);
        }

        return $recipe;
    }

    /**
     * Update an existing recipe.
     *
     * @return Recipe
     */
    public function update($recipe, array $data)
    {
        $steps = $data['steps'] ?? [];
        unset($data['steps']);
        $ingredients = $data['ingredients'] ?? [];
        unset($data['ingredients']);
        Recipe::where('id', $recipe->id)->update($data);
        if (isset($ingredients)) {
            $recipe->recipeIngredients()->delete();
            $this->saveRecipeIngredients($recipe, $ingredients);
        }
        if (isset($steps)) {
            $recipe->recipeSteps()->delete();
            $this->saveRecipeSteps($recipe, $steps);
        }

        return $recipe->refresh();
    }

    /**
     * Create a new unique slug.
     *
     * @param string $title
     *
     * @return string
     */
    private function createUniqueSlugStr($title)
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
     * Find a recipe by its slug or ID.
     *
     * @return Recipe|null
     */
    public function findByIdOrSlug($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $recipe = Recipe::find($idOrSlug);
        } else {
            $recipe = Recipe::where('slug', $idOrSlug)->first();
        }

        $recipe->steps = $recipe->recipeSteps ? $recipe->recipeSteps->sortBy('order')->values() : [];
        $recipeIngredients = $recipe->recipeIngredients ? $recipe->recipeIngredients->load('ingredient') : [];
        $recipe->ingredients = $recipeIngredients->map(function ($ri) {
            return [
                'id' => $ri->id,
                'name' => $ri->ingredient->name,
                'quantity' => rtrim(rtrim($ri->quantity, '0'), '.'),
                'unit' => $ri->unit,
                'measurement_unit' => $ri->ingredient->measurement_unit,
                'serving_quantity' => $ri->ingredient->serving_quantity,
                'calories' => $ri->ingredient->calories,
                'carbs' => $ri->ingredient->carbs,
                'protein' => $ri->ingredient->protein,
                'fat' => $ri->ingredient->fat,
            ];
        });

        return $recipe;
    }

    /**
     * Save recipe steps.
     *
     * @return void
     */
    private function saveRecipeSteps(Recipe $recipe, array $steps)
    {
        foreach ($steps as $index => $step) {
            $recipe->recipeSteps()->create([
                'description' => $step['description'],
                'order' => $step['order'] ?? $index + 1,
            ]);
        }
    }

    /**
     * Save recipe ingredients.
     *
     * @return void
     */
    private function saveRecipeIngredients(Recipe $recipe, array $ingredients)
    {
        \Log::debug('Saving recipe ingredients: '.json_encode($ingredients));
        foreach ($ingredients as $ingredient) {
            $search = Ingredient::query();
            $ingredientModel = $this->ingredientRepository->searchIngredients($search, $ingredient['name'])->first();
            \Log::debug('Ingredient search result: '.json_encode($ingredientModel));
            if ($ingredientModel) {
                $ingredient['id'] = $ingredientModel->id;
                $ingredient['measurement_unit'] = $ingredient['unit'] ?? $ingredientModel->measurement_unit;
            } else {
                $ingredientModel = Ingredient::create([
                    'name' => $ingredient['name'],
                    'measurement_unit' => $ingredient['unit'] ?? null,
                ]);
                $ingredient['id'] = $ingredientModel->id;
                \Log::debug('Created new ingredient: '.json_encode($ingredientModel));
            }
            $recipe->recipeIngredients()->create(
                [
                    'ingredient_id' => $ingredient['id'] ?? null,
                    'name' => $ingredient['name'] ?? null,
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'] ?? null,
                ]
            );
        }
    }
}
