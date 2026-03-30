<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrammarCheckController extends Controller
{
    public function index()
    {
        return view('grammar-check');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        return view('grammar-check', [
            'originalText' => $request->text,
            'correctedText' => 'Sample corrected text will appear here.',
            'explanation' => 'Sample explanation will appear here.',
        ]);
    }
}
