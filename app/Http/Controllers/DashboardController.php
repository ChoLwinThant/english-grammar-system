<?php

namespace App\Http\Controllers;

use App\Models\GrammarCheck;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalGrammarChecks = GrammarCheck::where('user_id', $userId)->count();
        $totalQuizAttempts = QuizAttempt::where('user_id', $userId)->count();
        $averageQuizScore = QuizAttempt::where('user_id', $userId)->avg('score');

        $latestGrammarCheck = GrammarCheck::where('user_id', $userId)
            ->latest()
            ->first();

        $latestQuizAttempt = QuizAttempt::with('topic.category')
            ->where('user_id', $userId)
            ->latest()
            ->first();

        return view('dashboard', compact(
            'totalGrammarChecks',
            'totalQuizAttempts',
            'averageQuizScore',
            'latestGrammarCheck',
            'latestQuizAttempt'
        ));
    }
}
