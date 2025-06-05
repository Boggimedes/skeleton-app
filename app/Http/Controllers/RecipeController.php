<?php

namespace App\Http\Controllers;

use App\Repositories\RecipeRepositoryInterface;

class RecipeController extends Controller
{
    protected $recipeRepository;

    public function __construct(RecipeRepositoryInterface $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function index()
    {
        $recipes = $this->recipeRepository->getAll();

        return response()->json($recipes);
    }

    public function show($id)
    {
        $recipe = $this->recipeRepository->findById($id);

        return response()->json($recipe);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'ingredients' => 'sometimes|array',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.measurement_unit' => 'nullable|string|max:50',
            'ingredients.*.id' => 'nullable|exists:ingredients,id',
            'steps' => 'sometimes|array',
            'steps.*.description' => 'required|string|max:1000',
            'steps.*.order' => 'required|integer|min:1',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
        ]);
        $recipe = $this->recipeRepository->create($validated);

        return response()->json($recipe, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
        ]);
        $recipe = $this->recipeRepository->update($id, $validated);

        return response()->json($recipe);
    }

    public function destroy($id)
    {
        $this->recipeRepository->delete($id);

        return response()->json(null, 204);
    }
}
