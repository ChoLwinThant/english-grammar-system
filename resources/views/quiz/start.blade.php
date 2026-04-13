@extends('layouts.app')

@section('content')
    <h2 class="mb-4">{{ $topic->name }} Quiz</h2>

    @if($questions->isEmpty())
        <div class="alert alert-info">No questions available for this topic.</div>
    @else
        <form method="POST" action="{{ route('quiz.submit', $topic) }}">
            @csrf

            @foreach($questions as $index => $question)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Question {{ $index + 1 }}</h5>
                        <p><strong>{{ $question->question_text }}</strong></p>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="A" id="q{{ $question->id }}a">
                            <label class="form-check-label" for="q{{ $question->id }}a">
                                A. {{ $question->option_a }}
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="B" id="q{{ $question->id }}b">
                            <label class="form-check-label" for="q{{ $question->id }}b">
                                B. {{ $question->option_b }}
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="C" id="q{{ $question->id }}c">
                            <label class="form-check-label" for="q{{ $question->id }}c">
                                C. {{ $question->option_c }}
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="D" id="q{{ $question->id }}d">
                            <label class="form-check-label" for="q{{ $question->id }}d">
                                D. {{ $question->option_d }}
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
    @endif
@endsection