<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrammarCheckController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\QuizController;

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/grammar-check', [GrammarCheckController::class, 'index'])->name('grammar.check');
    Route::post('/grammar-check', [GrammarCheckController::class, 'store'])->name('grammar.check.store');
    Route::get('/grammar-history', [GrammarCheckController::class, 'history'])->name('grammar.history');

    Route::get('/quiz', [QuizController::class, 'categories'])->name('quiz.categories');
    Route::get('/quiz/category/{category}', [QuizController::class, 'topics'])->name('quiz.topics');
    Route::get('/quiz/topic/{topic}', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/topic/{topic}', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz-history', [QuizController::class, 'history'])->name('quiz.history');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('topics', TopicController::class)->except(['show']);
    Route::resource('questions', QuestionController::class)->except(['show']);
});

require __DIR__ . '/auth.php';
