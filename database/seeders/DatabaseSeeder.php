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
            'database/scripts/levenshtine.sql',
        ];
        foreach ($paths as $path) {
            \DB::unprepared(file_get_contents($path));
        }
    }
}
