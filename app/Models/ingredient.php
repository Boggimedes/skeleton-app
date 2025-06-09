<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'measurement_unit', // e.g., 'grams', 'cups', 'tablespoons'
        'serving_quantity', // Quantity of the ingredient in the specified measurement unit
        'calories', // For future calorie calculation
        'carbs', // For future macronutrient calculation
        'protein', // For future macronutrient calculation
        'fat', // For future macronutrient calculation
        'type', // e.g., 'vegetable', 'fruit', 'dairy'
    ];
}
