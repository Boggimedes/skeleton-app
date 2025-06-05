<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Example route using a repository
Route::get('/recipes', function () {
    // Here you would typically use a repository to fetch data
    // $recipes = app(RecipeRepositoryInterface::class)->all();
    // return view('recipes.index', compact('recipes'));
});

Route::resource('ingredients', IngredientController::class);
// Additional routes can be defined here
// Route::get('/recipes/{id}', ...);
// Route::post('/recipes', ...);
// Route::put('/recipes/{id}', ...);
// Route::delete('/recipes/{id}', ...);