<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('topics')->insert([
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Present Simple',
                'difficulty' => Topic::DIFFICULTY_ELEMENTARY,
                'description' => 'Used for habits, routines, and general truths.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Past Simple',
                'difficulty' => Topic::DIFFICULTY_INTERMEDIATE,
                'description' => 'Used for completed actions in the past.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'name' => 'Future Simple',
                'difficulty' => Topic::DIFFICULTY_INTERMEDIATE,
                'description' => 'Used for actions that will happen in the future.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_id' => 2,
                'name' => 'Prepositions of Time',
                'difficulty' => Topic::DIFFICULTY_BASIC,
                'description' => 'Use words like in, on, and at for time expressions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category_id' => 2,
                'name' => 'Prepositions of Place',
                'difficulty' => Topic::DIFFICULTY_BASIC,
                'description' => 'Use words like in, on, under, and beside for location.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'category_id' => 3,
                'name' => 'Comparative Adjectives',
                'difficulty' => Topic::DIFFICULTY_ELEMENTARY,
                'description' => 'Compare two people or things.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'category_id' => 3,
                'name' => 'Superlative Adjectives',
                'difficulty' => Topic::DIFFICULTY_INTERMEDIATE,
                'description' => 'Describe the highest or lowest degree in a group.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'category_id' => 4,
                'name' => 'Subject-Verb Agreement',
                'difficulty' => Topic::DIFFICULTY_BASIC,
                'description' => 'Ensure subjects and verbs match in number and person.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'category_id' => 4,
                'name' => 'Articles',
                'difficulty' => Topic::DIFFICULTY_BASIC,
                'description' => 'Learn when to use a, an, and the.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'category_id' => 5,
                'name' => 'Modal Verbs',
                'difficulty' => Topic::DIFFICULTY_ADVANCED,
                'description' => 'Use can, should, must, may, and other modal verbs correctly.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
