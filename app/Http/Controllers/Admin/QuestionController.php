<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('topic.category')->latest()->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $topics = Topic::with('category')->orderBy('name')->get();
        return view('admin.questions.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
        ]);

        Question::create([
            'topic_id' => $request->topic_id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $topics = Topic::with('category')->orderBy('name')->get();
        return view('admin.questions.edit', compact('question', 'topics'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'explanation' => ['nullable', 'string'],
        ]);

        $question->update([
            'topic_id' => $request->topic_id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'explanation' => $request->explanation,
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
