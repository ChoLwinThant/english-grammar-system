<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function categories()
    {
        $categories = Category::orderBy('name')->get();
        return view('quiz.categories', compact('categories'));
    }

    public function topics(Category $category)
    {
        $topics = $category->topics()->orderBy('name')->get();
        return view('quiz.topics', compact('category', 'topics'));
    }

    public function start(Topic $topic)
    {
        $questions = $topic->questions()->get()->map(function ($question) {
            $question->display_options = collect([
                ['key' => 'A', 'text' => $question->option_a],
                ['key' => 'B', 'text' => $question->option_b],
                ['key' => 'C', 'text' => $question->option_c],
                ['key' => 'D', 'text' => $question->option_d],
            ])->shuffle()->values();

            return $question;
        });

        return view('quiz.start', compact('topic', 'questions'));
    }

    public function submit(Request $request, Topic $topic)
    {
        $questions = $topic->questions()->get();
        $score = 0;
        $results = [];

        foreach ($questions as $question) {
            $selectedAnswer = $request->input('question_' . $question->id);
            $isCorrect = $selectedAnswer === $question->correct_answer;

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question_text' => $question->question_text,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'selected_answer_text' => $selectedAnswer ? $question->{'option_' . strtolower($selectedAnswer)} : 'No answer selected',
                'correct_answer_text' => $question->{'option_' . strtolower($question->correct_answer)},
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'topic_id' => $topic->id,
            'score' => $score,
            'total_questions' => $questions->count(),
        ]);

        return view('quiz.result', compact('topic', 'score', 'questions', 'results'));
    }

    public function history()
    {
        $attempts = QuizAttempt::with('topic.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('quiz.history', compact('attempts'));
    }
}
