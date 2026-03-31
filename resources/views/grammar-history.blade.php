@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Grammar Check History</h2>
                <a href="{{ route('grammar.check') }}" class="btn btn-primary">New Grammar Check</a>
            </div>

            @if($grammarChecks->isEmpty())
                <div class="alert alert-info">
                    No grammar check history found.
                </div>
            @else
                @foreach($grammarChecks as $check)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Original Text</h5>
                            <p>{{ $check->original_text }}</p>

                            <h5 class="card-title mt-4">Corrected Text</h5>
                            <p>{{ $check->corrected_text }}</p>

                            <h5 class="card-title mt-4">Explanation</h5>
                            <p>{{ $check->explanation }}</p>

                            <small class="text-muted">
                                Checked on {{ $check->created_at->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection