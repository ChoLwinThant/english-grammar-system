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

                    <form method="POST" action="{{ route('grammar.check.store') }}" enctype="multipart/form-data">
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
                            <div class="form-text">You can paste text here or upload a file below.</div>
                        </div>

                        <div class="mb-4">
                            <label for="document" class="form-label">Upload a file</label>
                            <input
                                type="file"
                                name="document"
                                id="document"
                                class="form-control"
                                accept=".txt,.pdf"
                            >
                            <div class="form-text">Supported file types: TXT, PDF. Maximum file size: 5 MB.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Check Grammar</button>
                    </form>
                </div>
            </div>

            @isset($correctedText)
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        @isset($sourceLabel)
                            <p class="text-muted mb-3">Source: {{ $sourceLabel }}</p>
                        @endisset

                        @if(($report['mode'] ?? 'single') === 'single')
                            <h4 class="mb-3">Sentence Review</h4>
                            <div class="border rounded p-3 bg-light-subtle mb-3">
                                <p class="mb-2"><strong>Status:</strong> {{ !empty($report['is_correct']) ? 'Correct' : 'Needs correction' }}</p>
                                <p class="mb-2"><strong>Original Sentence:</strong></p>
                                <div style="white-space: pre-wrap;">{{ $report['original_sentence'] ?? $originalText ?? '' }}</div>
                            </div>

                            <h4 class="mb-3">Result</h4>
                            <div class="border rounded p-3 bg-light-subtle" style="white-space: pre-wrap;">{{ $report['corrected_sentence'] ?? $correctedText }}</div>

                            <h4 class="mb-3 mt-4">Explanation</h4>
                            <div class="border rounded p-3 bg-light-subtle" style="white-space: pre-wrap;">{{ $report['summary'] ?? $explanation }}</div>
                        @else
                            <h4 class="mb-3">Issue Report</h4>

                            @if(empty($report['issues']))
                                <div class="alert alert-success mb-3">
                                    No incorrect sentences were found.
                                </div>

                                <h4 class="mb-3">Comment</h4>
                                <div class="border rounded p-3 bg-light-subtle" style="white-space: pre-wrap;">{{ $report['summary'] ?? $explanation }}</div>
                            @else
                                @foreach($report['issues'] as $issue)
                                    <div class="border rounded p-3 bg-light-subtle mb-3">
                                        @if(!empty($issue['reference_label']))
                                            <p class="mb-2"><strong>{{ $issue['reference_label'] }}</strong></p>
                                        @endif
                                        <p class="mb-2"><strong>Original:</strong></p>
                                        <div class="mb-3" style="white-space: pre-wrap;">{{ $issue['original'] ?: 'Not provided' }}</div>
                                        <p class="mb-2"><strong>Corrected:</strong></p>
                                        <div class="mb-3" style="white-space: pre-wrap;">{{ $issue['corrected'] ?: 'Not provided' }}</div>
                                        <p class="mb-2"><strong>Explanation:</strong></p>
                                        <div style="white-space: pre-wrap;">{{ $issue['explanation'] ?: 'Not provided' }}</div>
                                    </div>
                                @endforeach

                                @if(!empty($report['summary']))
                                    <h4 class="mb-3 mt-4">Overall Comment</h4>
                                    <div class="border rounded p-3 bg-light-subtle" style="white-space: pre-wrap;">{{ $report['summary'] }}</div>
                                @endif
                            @endif
                        @endif

                        @if(!empty($downloadReady))
                            <a href="{{ route('grammar.check.download') }}" class="btn btn-outline-primary mt-4">
                                Download Result File
                            </a>
                        @endif
                    </div>
                </div>
            @endisset
        </div>
    </div>
@endsection
