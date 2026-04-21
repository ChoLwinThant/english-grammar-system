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

                        @foreach($question->display_options as $option)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="question_{{ $question->id }}"
                                    value="{{ $option['key'] }}"
                                    id="q{{ $question->id }}{{ strtolower($option['key']) }}"
                                >
                                <label class="form-check-label" for="q{{ $question->id }}{{ strtolower($option['key']) }}">
                                    {{ $option['key'] }}. {{ $option['text'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
    @endif
@endsection
