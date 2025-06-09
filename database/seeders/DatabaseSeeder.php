<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $paths = [
            'database/scripts/users.sql',
            'database/scripts/levenshtine.sql',
            'database/scripts/ingredients.sql',
            'database/scripts/recipes.sql',
            'database/scripts/recipe_ingredients.sql',
            'database/scripts/measurements.sql',
            'database/scripts/recipe_steps.sql',
        ];
        foreach ($paths as $path) {
            \DB::unprepared(file_get_contents($path));
        }
    }
}
