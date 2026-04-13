@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Select a Category</h2>

    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <a href="{{ route('quiz.topics', $category) }}" class="btn btn-primary">View Topics</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No categories available.</div>
        @endforelse
    </div>
@endsection