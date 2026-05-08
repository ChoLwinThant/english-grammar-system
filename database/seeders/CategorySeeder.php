<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Tenses',
                'description' => 'Learn how to use verbs correctly in present, past, and future time.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Prepositions',
                'description' => 'Understand how words show time, place, and direction relationships.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Adjectives',
                'description' => 'Learn how to describe and compare people, objects, and situations.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Grammar Rules',
                'description' => 'Master essential grammar structures and sentence formation rules.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Advanced Grammar',
                'description' => 'Explore advanced grammar topics for academic and professional English.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
