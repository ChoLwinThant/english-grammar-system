@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Grammar Checker</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('grammar.check.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="text" class="form-label">Enter your sentence or paragraph</label>
                            <textarea
                                name="text"
                                id="text"
                                rows="6"
                                class="form-control"
                                placeholder="Example: She go to school every day."
                            >{{ old('text', $originalText ?? '') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Check Grammar</button>
                    </form>
                </div>
            </div>

            @isset($correctedText)
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h4 class="mb-3">Corrected Text</h4>
                        <p>{{ $correctedText }}</p>

                        <h4 class="mb-3 mt-4">Explanation</h4>
                        <p>{{ $explanation }}</p>
                    </div>
                </div>
            @endisset
        </div>
    </div>
@endsection