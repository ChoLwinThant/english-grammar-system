@extends('layouts.app')

@section('content')
    <style>
        .history-entry {
            border-radius: 22px;
            overflow: hidden;
        }

        .history-entry .card-body {
            padding: 1.5rem;
        }

        .history-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .history-meta small {
            font-size: 0.9rem;
        }

        .history-status {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.42rem 0.8rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .history-status-improved {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fdba74;
        }

        .history-status-clean {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #86efac;
        }

        .history-panel {
            height: 100%;
            padding: 1.1rem 1.15rem;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            background: #fbfcff;
        }

        .history-panel-original {
            background: #fffdf8;
            border-color: #f1e6cf;
        }

        .history-panel-corrected {
            background: #f7fbff;
            border-color: #d9e7fb;
        }

        .history-label {
            margin-bottom: 0.65rem;
            color: #374151;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .history-text {
            margin: 0;
            white-space: pre-wrap;
            line-height: 1.75;
            color: #111827;
        }

        .history-issue-list {
            display: grid;
            gap: 1rem;
        }

        .history-issue {
            padding: 1.15rem;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%);
        }

        .history-issue-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .history-issue-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.72rem;
            border-radius: 999px;
            background: #f5f3ff;
            color: #6d28d9;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .history-explanation {
            padding: 1rem 1.1rem;
            border-radius: 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .history-study-grid {
            display: grid;
            gap: 1rem;
        }

        .history-study-card {
            padding: 1rem 1.1rem;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            border: 1px solid #dbe7ff;
        }
    </style>

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
                    @php
                        $referencePattern = '/^(Line\s+\d+|Page\s+\d+|Snippet only):\s*(.*)$/i';
                        $originalLines = preg_split("/\R/", (string) $check->original_text) ?: [];
                        $report = is_array($check->report_json ?? null) ? $check->report_json : null;

                        $correctedLines = collect(preg_split("/\R/", trim((string) $check->corrected_text)))
                            ->map(fn ($line) => trim($line))
                            ->filter()
                            ->values();

                        $explanationLines = collect(preg_split("/\R/", trim((string) $check->explanation)))
                            ->map(fn ($line) => trim($line))
                            ->filter()
                            ->values();

                        $overallComment = null;
                        $issueExplanationLines = $explanationLines->values();
                        $overallCommentIndex = $issueExplanationLines->search(fn ($line) => str_starts_with($line, 'Overall comment:'));

                        if ($overallCommentIndex !== false) {
                            $overallComment = trim(\Illuminate\Support\Str::after($issueExplanationLines[$overallCommentIndex], 'Overall comment:'));
                            $issueExplanationLines = $issueExplanationLines->forget($overallCommentIndex)->values();
                        }

                        $allCorrectedStructured = $correctedLines->isNotEmpty()
                            && $correctedLines->every(fn ($line) => preg_match($referencePattern, $line) === 1);

                        $allExplanationsStructured = $issueExplanationLines->isNotEmpty()
                            && $issueExplanationLines->every(fn ($line) => preg_match($referencePattern, $line) === 1);

                        $parsedIssues = collect();
                        $studyPlan = is_array($report['study_plan'] ?? null) ? $report['study_plan'] : null;

                        if (($report['mode'] ?? null) === 'multi' && !empty($report['issues']) && is_array($report['issues'])) {
                            $parsedIssues = collect($report['issues'])
                                ->filter(fn ($issue) => is_array($issue))
                                ->map(function (array $issue) use ($check) {
                                    $reference = trim((string) ($issue['reference_label'] ?? '')) ?: 'Snippet only';
                                    $originalSnippet = trim((string) ($issue['original'] ?? ''));

                                    return [
                                        'reference' => $reference,
                                        'original' => $originalSnippet !== '' ? $originalSnippet : 'Original snippet was not stored for this entry.',
                                        'corrected' => trim((string) ($issue['corrected'] ?? '')) ?: 'No corrected text returned.',
                                        'explanation' => trim((string) ($issue['explanation'] ?? '')) ?: 'No explanation returned.',
                                    ];
                                })
                                ->values();

                            $overallComment = trim((string) ($report['summary'] ?? '')) ?: $overallComment;
                        } elseif ($allCorrectedStructured && $allExplanationsStructured) {
                            $explanationMap = $issueExplanationLines
                                ->mapWithKeys(function ($line) use ($referencePattern) {
                                    preg_match($referencePattern, $line, $matches);

                                    return [trim($matches[1]) => trim($matches[2])];
                                });

                            $parsedIssues = $correctedLines->map(function ($line) use ($referencePattern, $explanationMap, $check, $originalLines) {
                                preg_match($referencePattern, $line, $matches);

                                $reference = trim($matches[1]);
                                $originalSnippet = 'Original snippet was not stored for this entry.';

                                if (preg_match('/^Line\s+(\d+)$/i', $reference, $lineMatches)) {
                                    $lineNumber = (int) $lineMatches[1];
                                    $originalSnippet = trim($originalLines[$lineNumber - 1] ?? '') ?: $check->original_text;
                                }

                                return [
                                    'reference' => $reference,
                                    'original' => $originalSnippet,
                                    'corrected' => trim($matches[2]),
                                    'explanation' => $explanationMap[$reference] ?? 'No explanation returned.',
                                ];
                            });
                        }

                        $noCorrectionsNeeded = trim((string) $check->corrected_text) === 'No corrections needed.';
                        $showIssueComparison = $parsedIssues->isNotEmpty();
                        $statusLabel = $noCorrectionsNeeded ? 'No corrections needed' : 'Corrections available';
                        $statusClass = $noCorrectionsNeeded ? 'history-status-clean' : 'history-status-improved';
                    @endphp

                    <div class="card shadow-sm mb-4 history-entry">
                        <div class="card-body">
                            <div class="history-meta">
                                <span class="history-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                <small class="text-muted">
                                    Checked on {{ $check->created_at->format('d M Y, h:i A') }}
                                </small>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-lg-6">
                                    <div class="history-panel history-panel-original">
                                        <div class="history-label">Original Text</div>
                                        <p class="history-text">{{ $check->original_text }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="history-panel history-panel-corrected">
                                        <div class="history-label">{{ $showIssueComparison ? 'Correction Summary' : 'Corrected Text' }}</div>
                                        <p class="history-text">{{ $check->corrected_text }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($showIssueComparison)
                                <h5 class="card-title mt-2 mb-3">Issue-by-Issue Comparison</h5>
                                <div class="history-issue-list">
                                    @foreach($parsedIssues as $issue)
                                        <div class="history-issue">
                                            <div class="history-issue-head">
                                                <span class="history-issue-tag">{{ $issue['reference'] }}</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <div class="history-panel history-panel-original">
                                                        <div class="history-label">From Your Original Text</div>
                                                        <p class="history-text">{{ $issue['original'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="history-panel history-panel-corrected">
                                                        <div class="history-label">Suggested Correction</div>
                                                        <p class="history-text">{{ $issue['corrected'] }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="history-explanation mt-3">
                                                <div class="history-label">Why This Change Was Made</div>
                                                <p class="history-text">{{ $issue['explanation'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($overallComment)
                                    <h5 class="card-title mt-4 mb-3">Overall Comment</h5>
                                    <div class="history-explanation">
                                        <p class="history-text">{{ $overallComment }}</p>
                                    </div>
                                @endif
                            @else
                                <h5 class="card-title mt-4 mb-3">Explanation</h5>
                                <div class="history-explanation">
                                    <p class="history-text">{{ $overallComment ?: $check->explanation }}</p>
                                </div>
                            @endif

                            @if(!empty($studyPlan['items']))
                                <h5 class="card-title mt-4 mb-3">What to Study Next</h5>
                                @if(!empty($studyPlan['summary']))
                                    <div class="history-explanation mb-3">
                                        <p class="history-text">{{ $studyPlan['summary'] }}</p>
                                    </div>
                                @endif

                                <div class="history-study-grid">
                                    @foreach($studyPlan['items'] as $item)
                                        <div class="history-study-card">
                                            <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                                                <strong>{{ $item['title'] ?? 'Grammar practice' }}</strong>
                                                <span class="history-issue-tag">{{ ucfirst($item['priority'] ?? 'medium') }} priority</span>
                                            </div>

                                            <p class="history-text mb-3">{{ $item['reason'] ?? 'Targeted review will help reinforce this grammar area.' }}</p>

                                            <div class="d-flex flex-wrap gap-2">
                                                @if(!empty($item['topic']['id']))
                                                    <a href="{{ route('quiz.start', $item['topic']['id']) }}" class="btn btn-sm btn-primary">
                                                        Practice Topic
                                                    </a>
                                                @endif

                                                @foreach(($item['resources'] ?? []) as $resource)
                                                    <a
                                                        href="{{ $resource['url'] }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="btn btn-sm btn-outline-secondary"
                                                    >
                                                        {{ $resource['name'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
