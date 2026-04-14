@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Topics in {{ $category->name }}</h2>

    <div class="row">
        @forelse($topics as $topic)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $topic->name }}</h5>
                        <a href="{{ route('quiz.start', $topic) }}" class="btn btn-success">Start Quiz</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No topics available in this category.</div>
        @endforelse
    </div>
@endsection