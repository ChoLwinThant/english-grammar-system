@extends('layouts.app')

@section('content')
    <div class="p-4 p-md-5 bg-white rounded shadow-sm">
        <h1 class="display-6 fw-bold">AI Grammar Checker</h1>
        <p class="lead mb-4">
            A simple web application to help users check English grammar and practice through quizzes.
        </p>
        <a href="{{ route('grammar.check') }}" class="btn btn-primary">Try Grammar Checker</a>
    </div>
@endsection