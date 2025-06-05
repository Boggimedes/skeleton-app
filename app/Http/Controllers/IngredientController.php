<?php

namespace App\Http\Controllers;

use App\Repositories\IngredientRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\IngredientRequest;

class IngredientController extends Controller
{
    protected $ingredientRepository;

    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;
    }

    public function index()
    {
        $ingredients = $this->ingredientRepository->getAllIngredients();
        return response()->json($ingredients);
    }

    public function show($id)
    {
        $ingredient = $this->ingredientRepository->getIngredientById($id);
        return response()->json($ingredient);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $ingredients = $this->ingredientRepository->searchIngredients($query);
        return response()->json($ingredients);
    }

    public function store(IngredientRequest $request)
    {
        $validated = $request->validated();

        $ingredient = $this->ingredientRepository->createIngredient($validated);
        return response()->json($ingredient, 201);
    }

    public function update(IngredientRequest $request, $id)
    {
        $validated = $request->validated();

        $ingredient = $this->ingredientRepository->updateIngredient($id, $validated);
        return response()->json($ingredient);
    }

    public function destroy($id)
    {
        $this->ingredientRepository->deleteIngredient($id);
        return response()->json(null, 204);
    }
}