<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('cors')->get('/ingredients', [IngredientController::class, 'index']);
// Route::middleware('cors')->get('/ingredients/search', [IngredientController::class, 'search']);

Route::middleware('cors')->get('/recipes', [RecipeController::class, 'index']);
Route::middleware('cors')->get('/recipe/{idOrSlug}', [RecipeController::class, 'show']);
Route::middleware('cors')->get('/recipes/search', [RecipeController::class, 'search']);
// Route::middleware('cors')->resource('ingredients', IngredientController::class);
Route::middleware('cors')->resource('recipe', RecipeController::class);
