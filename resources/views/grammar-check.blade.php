@extends('layouts.app')

@section('content')
    <style>
        .grammar-check-form {
            position: relative;
        }

        .grammar-loading-state {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(2px);
            z-index: 2;
        }

        .grammar-loading-state.is-visible {
            display: flex;
        }

        .grammar-loading-card {
            min-width: 240px;
            max-width: 360px;
            text-align: center;
            border: 1px solid rgba(74, 26, 248, 0.16);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 1rem 2rem rgba(47, 38, 87, 0.336);
            padding: 1.25rem;
        }

        .grammar-loading-spinner {
            color: #4a1af8;
        }

        .grammar-loading-text {
            margin-top: 0.9rem;
            margin-bottom: 0.35rem;
            font-weight: 600;
            color: #261f37;
        }

        .grammar-loading-subtext {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .study-plan-grid {
            display: grid;
            gap: 1rem;
        }

        .study-plan-card {
            border: 1px solid #dbe7ff;
            border-radius: 1rem;
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            padding: 1rem;
        }

        .study-plan-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            background: #e0ecff;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .study-plan-links a {
            text-decoration: none;
        }
    </style>

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

                    <form
                        method="POST"
                        action="{{ route('grammar.check.store') }}"
                        enctype="multipart/form-data"
                        class="grammar-check-form"
                        id="grammarCheckForm"
                    >
                        @csrf

                        <div class="grammar-loading-state" id="grammarLoadingState" aria-live="polite" aria-hidden="true">
                            <div class="grammar-loading-card" role="status">
                                <div class="spinner-border grammar-loading-spinner" aria-hidden="true"></div>
                                <p class="grammar-loading-text">Checking your grammar...</p>
                                <p class="grammar-loading-subtext">Please wait while we review your text.</p>
                            </div>
                        </div>

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

                        <button type="submit" class="btn btn-primary" id="grammarCheckSubmitButton">
                            <span class="default-label">Check Grammar</span>
                            <span class="loading-label d-none">Checking...</span>
                        </button>
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

                        @php
                            $studyPlanData = $studyPlan ?? ($report['study_plan'] ?? null);
                        @endphp

                        @if(!empty($studyPlanData['items']))
                            <h4 class="mb-3 mt-4">What to Study Next</h4>
                            @if(!empty($studyPlanData['summary']))
                                <div class="border rounded p-3 bg-light-subtle mb-3" style="white-space: pre-wrap;">{{ $studyPlanData['summary'] }}</div>
                            @endif

                            <div class="study-plan-grid">
                                @foreach($studyPlanData['items'] as $item)
                                    <div class="study-plan-card">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                            <div>
                                                <h5 class="mb-1">{{ $item['title'] }}</h5>
                                                @if(!empty($item['topic']['category_name']) || !empty($item['topic']['difficulty']))
                                                    <p class="text-muted mb-0">
                                                        {{ $item['topic']['category_name'] ?? 'Grammar' }}
                                                        @if(!empty($item['topic']['difficulty']))
                                                            • {{ $item['topic']['difficulty'] }}
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="study-plan-badge">{{ ucfirst($item['priority'] ?? 'medium') }} priority</span>
                                        </div>

                                        <p class="mb-3">{{ $item['reason'] }}</p>

                                        @if(!empty($item['topic']['description']))
                                            <p class="text-muted mb-3">{{ $item['topic']['description'] }}</p>
                                        @endif

                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            @if(!empty($item['topic']['id']))
                                                <a href="{{ route('quiz.start', $item['topic']['id']) }}" class="btn btn-primary btn-sm">
                                                    Practice This Topic
                                                </a>
                                            @endif
                                            <a href="{{ route('quiz.categories') }}" class="btn btn-outline-primary btn-sm">
                                                Browse All Quiz Topics
                                            </a>
                                        </div>

                                        @if(!empty($item['resources']))
                                            <div class="study-plan-links">
                                                <p class="fw-semibold mb-2">Helpful online resources</p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($item['resources'] as $resource)
                                                        <a
                                                            href="{{ $resource['url'] }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="btn btn-outline-secondary btn-sm"
                                                        >
                                                            {{ $resource['name'] }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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

    <script>
        (() => {
            const form = document.getElementById('grammarCheckForm');
            const loadingState = document.getElementById('grammarLoadingState');
            const submitButton = document.getElementById('grammarCheckSubmitButton');

            if (!form || !loadingState || !submitButton) {
                return;
            }

            const defaultLabel = submitButton.querySelector('.default-label');
            const loadingLabel = submitButton.querySelector('.loading-label');

            form.addEventListener('submit', () => {
                loadingState.classList.add('is-visible');
                loadingState.setAttribute('aria-hidden', 'false');
                submitButton.disabled = true;
                defaultLabel?.classList.add('d-none');
                loadingLabel?.classList.remove('d-none');
            });
        })();
    </script>
@endsection
