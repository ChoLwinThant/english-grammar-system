<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrammarCheckController;

Route::get('/', function () {
    return view('home');
});

Route::get('/grammar-check', [GrammarCheckController::class, 'index'])->name('grammar.check');
Route::post('/grammar-check', [GrammarCheckController::class, 'store'])->name('grammar.check.store');
Route::get('/grammar-history', [GrammarCheckController::class, 'history'])->name('grammar.history');
