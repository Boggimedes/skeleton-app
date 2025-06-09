<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeRequest;
use App\Repositories\RecipeRepository;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected $recipeRepository;

    /**
     * RecipeController constructor.
     *
     * @param RecipeRepository $recipeRepository the repository instance for managing recipe data
     */
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Display a listing of recipes.
     *
     * This method retrieves all recipes from the recipe repository
     * and returns them as a JSON response.
     *
     * @param Request $request the incoming HTTP request instance
     *
     * @return \Illuminate\Http\JsonResponse a JSON response containing the list of recipes
     */
    public function index(Request $request)
    {
        $recipes = $this->recipeRepository->all();

        return response()->json($recipes);
    }

    /**
     * Handles the search functionality for recipes.
     *
     * @param Request $request the HTTP request instance containing search parameters
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the search results.
     *
     * The method processes the following search parameters from the request:
     * - `keyword`: A string to search for in recipe titles or descriptions (default is an empty string).
     * - `ingredient`: A string to filter recipes by specific ingredients (optional).
     * - `author`: A string to filter recipes by specific authors (optional).
     *
     * The search is performed using the `recipeRepository`:
     * - If `ingredient` is provided, the results are further filtered by ingredients.
     * - If `author` is provided, the results are further filtered by authors.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword', '') ?? '';
        $ingredient = $request->input('ingredient', '') ?? '';
        $author = $request->input('email', '') ?? '';
        $search = $this->recipeRepository->searchByKeyword($keyword);
        if ($ingredient) {
            $search = $this->recipeRepository->searchRecipeByIngredients($search, $ingredient);
        }
        if ($author) {
            $search = $this->recipeRepository->searchRecipeByAuthors($search, $author);
        }

        return response()->json($search->get());
    }

    /** Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($idOrSlug);

        return response()->json($recipe);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeRequest $request)
    {
        $validated = $request->validated();
        $recipe = $this->recipeRepository->create($validated);

        return response()->json($recipe, 201);
    }

    /**
     * Update an existing recipe with the provided data.
     *
     * @param Request $request the HTTP request containing the recipe data
     *
     * @return \Illuminate\Http\JsonResponse a JSON response containing the updated recipe
     *
     * @throws \Illuminate\Validation\ValidationException if the validation of the request data fails
     */
    public function update(RecipeRequest $request, $idOrSlug)
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($idOrSlug);
        $validated = $request->validated();
        $recipe = $this->recipeRepository->update($recipe, $validated);

        return response()->json($recipe);
    }

    /**
     * Remove the specified recipe from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->recipeRepository->delete($id);

        return response()->json(null, 204);
    }
}
