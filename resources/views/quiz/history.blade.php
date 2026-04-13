@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Quiz History</h2>

    @if($attempts->isEmpty())
        <div class="alert alert-info">No quiz attempts found.</div>
    @else
        @foreach($attempts as $attempt)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5>{{ $attempt->topic->name }}</h5>
                    <p><strong>Category:</strong> {{ $attempt->topic->category->name }}</p>
                    <p><strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->total_questions }}</p>
                    <small class="text-muted">
                        Attempted on {{ $attempt->created_at->format('d M Y, h:i A') }}
                    </small>
                </div>
            </div>
        @endforeach
    @endif
@endsection