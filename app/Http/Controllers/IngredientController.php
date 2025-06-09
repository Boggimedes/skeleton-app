<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientRequest;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    protected $ingredientRepository;

    public function __construct(IngredientRequest $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;
    }

    public function index()
    {
        $ingredients = $this->ingredientRepository->all();

        return response()->json($ingredients);
    }

    public function show($id)
    {
        $ingredient = $this->ingredientRepository->find($id);

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

        $ingredient = $this->ingredientRepository->create($validated);

        return response()->json($ingredient, 201);
    }

    public function update(IngredientRequest $request, $id)
    {
        $validated = $request->validated();

        $ingredient = $this->ingredientRepository->update($id, $validated);

        return response()->json($ingredient);
    }

    public function destroy($id)
    {
        $this->ingredientRepository->deleteIngredient($id);

        return response()->json(null, 204);
    }
}
