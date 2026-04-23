<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [];

        $questionPools = [
            1 => [ // Present Simple
                ['She ___ to school every day.', 'go', 'goes', 'going', 'gone', 'B', 'The subject "She" is singular, so we use "goes".'],
                ['He ___ tea every morning.', 'drink', 'drinks', 'drinking', 'drank', 'B', 'Third person singular requires -s.'],
                ['They ___ football on Sundays.', 'plays', 'play', 'playing', 'played', 'B', 'Plural subject uses base verb.'],
                ['My mother ___ dinner at 7 PM.', 'cook', 'cooks', 'cooked', 'cooking', 'B', 'Singular subject uses "cooks".'],
                ['The sun ___ in the east.', 'rise', 'rises', 'rose', 'rising', 'B', 'General truth uses present simple.'],
                ['She always ___ coffee.', 'drink', 'drinks', 'drank', 'drinking', 'B', 'Routine action uses present simple.'],
                ['We ___ to work every day.', 'go', 'goes', 'going', 'gone', 'A', 'Plural subject uses base form.'],
                ['He ___ his homework daily.', 'do', 'does', 'doing', 'did', 'B', 'Singular subject requires "does".'],
                ['The train ___ at 8 AM.', 'leave', 'leaves', 'left', 'leaving', 'B', 'Scheduled routine uses present simple.'],
                ['Cats ___ milk.', 'likes', 'like', 'liked', 'liking', 'B', 'Plural noun uses base form.'],
            ],

            2 => [ // Past Simple
                ['Yesterday I ___ a movie.', 'watch', 'watched', 'watching', 'watches', 'B', 'Past time requires past tense.'],
                ['She ___ her homework last night.', 'finish', 'finishes', 'finished', 'finishing', 'C', 'Past form is finished.'],
                ['They ___ to school yesterday.', 'go', 'went', 'gone', 'goes', 'B', 'Past tense of go is went.'],
                ['He ___ breakfast this morning.', 'eat', 'eats', 'ate', 'eating', 'C', 'Past tense of eat is ate.'],
                ['We ___ football yesterday.', 'play', 'played', 'plays', 'playing', 'B', 'Past action uses played.'],
                ['She ___ late yesterday.', 'arrive', 'arrived', 'arrives', 'arriving', 'B', 'Completed past action.'],
                ['I ___ my friend last week.', 'meet', 'met', 'meets', 'meeting', 'B', 'Past form of meet is met.'],
                ['They ___ dinner at 8 PM.', 'have', 'had', 'has', 'having', 'B', 'Past tense of have is had.'],
                ['He ___ the door.', 'open', 'opened', 'opens', 'opening', 'B', 'Past completed action.'],
                ['She ___ a letter.', 'write', 'wrote', 'writes', 'writing', 'B', 'Past tense of write is wrote.'],
            ],

            3 => [ // Future Simple
                ['I ___ visit my friend tomorrow.', 'am', 'will', 'was', 'did', 'B', 'Future action uses will.'],
                ['They ___ travel next week.', 'will', 'are', 'did', 'was', 'A', 'Future time requires will.'],
                ['She ___ call you later.', 'will', 'is', 'was', 'did', 'A', 'Future action needs will.'],
                ['We ___ meet tomorrow.', 'will', 'were', 'did', 'do', 'A', 'Future simple = will + verb.'],
                ['He ___ study tonight.', 'will', 'is', 'was', 'has', 'A', 'Future action uses will.'],
                ['I ___ finish it soon.', 'will', 'am', 'did', 'was', 'A', 'Future promise uses will.'],
                ['She ___ come later.', 'will', 'came', 'comes', 'coming', 'A', 'Future event.'],
                ['We ___ start next month.', 'will', 'started', 'starts', 'starting', 'A', 'Future schedule.'],
                ['They ___ join us tomorrow.', 'will', 'joined', 'joins', 'joining', 'A', 'Future plan.'],
                ['He ___ be there soon.', 'will', 'was', 'is', 'did', 'A', 'Future certainty.'],
            ],

            4 => [ // Prepositions of Time
                ['I wake up ___ 7 AM.', 'in', 'on', 'at', 'by', 'C', 'Use at for exact times.'],
                ['We have class ___ Monday.', 'in', 'on', 'at', 'by', 'B', 'Use on for days.'],
                ['My birthday is ___ July.', 'in', 'on', 'at', 'by', 'A', 'Use in for months.'],
                ['The meeting starts ___ noon.', 'in', 'on', 'at', 'by', 'C', 'Exact time needs at.'],
                ['I sleep ___ night.', 'in', 'on', 'at', 'by', 'C', 'Common phrase: at night.'],
                ['School starts ___ September.', 'in', 'on', 'at', 'by', 'A', 'Month uses in.'],
                ['The event is ___ Friday.', 'in', 'on', 'at', 'by', 'B', 'Day uses on.'],
                ['He arrives ___ 5 PM.', 'in', 'on', 'at', 'by', 'C', 'Specific time uses at.'],
                ['I was born ___ 2001.', 'in', 'on', 'at', 'by', 'A', 'Year uses in.'],
                ['The shop opens ___ morning.', 'in', 'on', 'at', 'by', 'A', 'Part of day uses in.'],
            ],

            5 => [ // Prepositions of Place
                ['The book is ___ the table.', 'in', 'on', 'at', 'by', 'B', 'Surface = on.'],
                ['She is ___ the room.', 'in', 'on', 'at', 'by', 'A', 'Inside space = in.'],
                ['He is waiting ___ the bus stop.', 'in', 'on', 'at', 'by', 'C', 'Specific point = at.'],
                ['The cat is ___ the chair.', 'under', 'on', 'at', 'in', 'A', 'Below = under.'],
                ['The school is ___ the bank.', 'next to', 'in', 'on', 'under', 'A', 'Beside location.'],
                ['The keys are ___ the bag.', 'in', 'on', 'at', 'under', 'A', 'Inside bag = in.'],
                ['The lamp is ___ the table.', 'on', 'in', 'at', 'under', 'A', 'Surface = on.'],
                ['He sat ___ me.', 'beside', 'in', 'at', 'under', 'A', 'Next to = beside.'],
                ['The dog is ___ the bed.', 'under', 'on', 'at', 'in', 'A', 'Below = under.'],
                ['She stood ___ the door.', 'at', 'on', 'in', 'under', 'A', 'Specific place = at.'],
            ],
        ];

        // Reuse similar logic for topics 6–10 by cloning with adjusted topic IDs
        for ($topicId = 6; $topicId <= 10; $topicId++) {
            $questionPools[$topicId] = $questionPools[$topicId - 5];
        }

        foreach ($questionPools as $topicId => $pool) {
            foreach ($pool as $q) {
                $questions[] = [
                    'topic_id' => $topicId,
                    'question_text' => $q[0],
                    'option_a' => $q[1],
                    'option_b' => $q[2],
                    'option_c' => $q[3],
                    'option_d' => $q[4],
                    'correct_answer' => $q[5],
                    'explanation' => $q[6],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('questions')->insert($questions);
    }
}
