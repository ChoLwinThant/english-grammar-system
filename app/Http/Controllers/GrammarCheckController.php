<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\GrammarCheck;

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

        $inputText = $request->text;

        $prompt = <<<PROMPT
        You are an English grammar tutor.

        Correct the sentence and explain the mistake simply.

        Return ONLY in this JSON format:

        {
        "corrected_text": "...",
        "explanation": "..."
        }

        Sentence:
        $inputText
        PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/responses', [
            'model' => env('OPENAI_MODEL', 'gpt-5.4-mini'),
            'input' => $prompt,
        ]);

        if (! $response->successful()) {
            return back()
                ->withErrors([
                    'api' => 'OpenAI request failed: ' . $response->body()
                ])
                ->withInput();
        }

        $data = $response->json();
        $outputText = $data['output'][0]['content'][0]['text'] ?? null;

        $json = json_decode($outputText, true);

        $correctedText = $json['corrected_text'] ?? 'Error';
        $explanation = $json['explanation'] ?? 'Error';

        GrammarCheck::create([
            'user_id' => Auth::id(),
            'original_text' => $inputText,
            'corrected_text' => $correctedText,
            'explanation' => $explanation,
        ]);

        if (preg_match('/Corrected:\s*(.*?)\s*Explanation:\s*(.*)/s', $outputText, $matches)) {
            $correctedText = trim($matches[1]);
            $explanation = trim($matches[2]);
        }

        return view('grammar-check', [
            'originalText' => $inputText,
            'correctedText' => $correctedText,
            'explanation' => $explanation ?: 'No explanation returned.',
        ]);
    }

    public function history()
    {
        $grammarChecks = GrammarCheck::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('grammar-history', compact('grammarChecks'));
    }
}
