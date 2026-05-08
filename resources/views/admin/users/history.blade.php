@extends('layouts.app')

@section('content')
    <style>
        .admin-history-hero {
            padding: 1.5rem;
            border: 1px solid var(--border);
            border-radius: 24px;
            background: linear-gradient(135deg, #ffffff 0%, #f7f7ff 55%, #eef4ff 100%);
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
        }

        .admin-history-user-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.85rem;
        }

        .admin-history-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin: 1.25rem 0;
        }

        .admin-history-stat {
            padding: 1.1rem 1.2rem;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.84);
        }

        .admin-history-stat-label {
            margin: 0 0 0.35rem;
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .admin-history-stat-value {
            margin: 0;
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .admin-history-stat-note {
            margin: 0.5rem 0 0;
            color: #64748b;
            font-size: 0.92rem;
        }

        .admin-history-filter-card {
            margin: 1.25rem 0;
            padding: 1.25rem;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #ffffff;
        }

        .admin-history-filter-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .admin-history-columns {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 1.25rem;
        }

        .admin-history-section {
            padding: 1.35rem;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: linear-gradient(180deg, #ffffff 0%, #fcfcff 100%);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .admin-history-section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .admin-history-section-head p {
            margin: 0.35rem 0 0;
            color: var(--text-muted);
        }

        .admin-history-list {
            display: grid;
            gap: 1rem;
        }

        .admin-history-item {
            padding: 1rem 1.05rem;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            background: #fbfcff;
        }

        .admin-history-item-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.8rem;
        }

        .admin-history-item-copy {
            margin: 0;
            color: #1f2937;
            line-height: 1.65;
            white-space: pre-wrap;
        }

        .admin-history-item-copy.clamp-compact {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .admin-history-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.38rem 0.72rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-history-chip-neutral {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .admin-history-chip-success {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #86efac;
        }

        .admin-history-chip-warning {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fdba74;
        }

        .admin-history-subtle {
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        .admin-history-inline-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            margin-top: 0.85rem;
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        @media (max-width: 1199.98px) {
            .admin-history-grid,
            .admin-history-filter-grid,
            .admin-history-columns {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .admin-history-grid,
            .admin-history-filter-grid,
            .admin-history-columns {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="admin-page-head">
        <div>
            <h2 class="mb-0">User History</h2>
            <p>Review grammar checks and quiz attempts for a single account.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-admin">Back to Users</a>
    </div>

    <div class="admin-history-hero">
        <h3 class="mb-0">{{ $user->name }}</h3>
        <div class="admin-history-user-meta">
            <span class="text-muted">{{ $user->email }}</span>
            <span class="badge-role text-capitalize">{{ $user->role }}</span>
            <span class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="admin-history-grid">
        <div class="admin-history-stat">
            <p class="admin-history-stat-label">Grammar Checks</p>
            <p class="admin-history-stat-value">{{ $summary['grammar_checks_count'] }}</p>
            <p class="admin-history-stat-note">Total filtered grammar reviews.</p>
        </div>
        <div class="admin-history-stat">
            <p class="admin-history-stat-label">Corrections Found</p>
            <p class="admin-history-stat-value">{{ $summary['grammar_checks_with_corrections_count'] }}</p>
            <p class="admin-history-stat-note">Entries where the system suggested changes.</p>
        </div>
        <div class="admin-history-stat">
            <p class="admin-history-stat-label">Quiz Attempts</p>
            <p class="admin-history-stat-value">{{ $summary['quiz_attempts_count'] }}</p>
            <p class="admin-history-stat-note">Total filtered quiz submissions.</p>
        </div>
        <div class="admin-history-stat">
            <p class="admin-history-stat-label">Average Quiz Score</p>
            <p class="admin-history-stat-value">{{ $summary['average_quiz_percentage'] !== null ? $summary['average_quiz_percentage'] . '%' : '-' }}</p>
            <p class="admin-history-stat-note">
                Latest activity:
                {{ $summary['latest_activity_at'] ? $summary['latest_activity_at']->format('d M Y, h:i A') : 'No activity yet' }}
            </p>
        </div>
    </div>

    <div class="admin-history-filter-card">
        <form method="GET" action="{{ route('admin.users.history', $user) }}">
            <div class="admin-history-filter-grid">
                <div>
                    <label for="from_date" class="form-label">From date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div>
                    <label for="to_date" class="form-label">To date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div>
                    <label for="topic_id" class="form-label">Quiz topic</label>
                    <select name="topic_id" id="topic_id" class="form-select">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ (string) $selectedTopicId === (string) $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }} ({{ $topic->category->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary align-self-end">Apply Filters</button>
                    @if($fromDate !== '' || $toDate !== '' || $selectedTopicId !== '')
                        <a href="{{ route('admin.users.history', $user) }}" class="btn btn-outline-primary align-self-end">Clear</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="admin-history-columns">
        <section class="admin-history-section">
            <div class="admin-history-section-head">
                <div>
                    <h4 class="mb-0">Grammar Check History</h4>
                    <p>Original text, corrections, and explanation snapshots.</p>
                </div>
                <span class="admin-history-chip admin-history-chip-neutral">{{ $grammarChecks->count() }} entries</span>
            </div>

            @if($grammarChecks->isEmpty())
                <div class="alert alert-info mb-0">No grammar checks match the current filters.</div>
            @else
                <div class="admin-history-list">
                    @foreach($grammarChecks as $check)
                        @php
                            $report = is_array($check->report_json ?? null) ? $check->report_json : [];
                            $hasCorrections = (($report['mode'] ?? null) === 'single' && !($report['is_correct'] ?? false))
                                || (($report['mode'] ?? null) === 'multi' && !empty($report['issues']));
                        @endphp

                        <article class="admin-history-item">
                            <div class="admin-history-item-top">
                                <span class="admin-history-chip {{ $hasCorrections ? 'admin-history-chip-warning' : 'admin-history-chip-success' }}">
                                    {{ $hasCorrections ? 'Corrections Suggested' : 'Looks Correct' }}
                                </span>
                                <span class="admin-history-subtle">{{ $check->created_at->format('d M Y, h:i A') }}</span>
                            </div>

                            <p class="admin-history-subtle mb-2">Original text</p>
                            <p class="admin-history-item-copy clamp-compact">{{ $check->original_text }}</p>

                            <p class="admin-history-subtle mb-2 mt-3">Correction summary</p>
                            <p class="admin-history-item-copy clamp-compact">{{ $check->corrected_text }}</p>

                            <p class="admin-history-subtle mb-2 mt-3">Explanation</p>
                            <p class="admin-history-item-copy clamp-compact">{{ $check->explanation }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="admin-history-section">
            <div class="admin-history-section-head">
                <div>
                    <h4 class="mb-0">Quiz Attempt History</h4>
                    <p>Topic, category, score, and timing for each attempt.</p>
                </div>
                <span class="admin-history-chip admin-history-chip-neutral">{{ $quizAttempts->count() }} attempts</span>
            </div>

            @if($quizAttempts->isEmpty())
                <div class="alert alert-info mb-0">No quiz attempts match the current filters.</div>
            @else
                <div class="admin-history-list">
                    @foreach($quizAttempts as $attempt)
                        @php
                            $percentage = $attempt->total_questions > 0
                                ? round(($attempt->score / $attempt->total_questions) * 100)
                                : 0;
                        @endphp

                        <article class="admin-history-item">
                            <div class="admin-history-item-top">
                                <div>
                                    <h5 class="mb-1">{{ $attempt->topic->name }}</h5>
                                    <p class="admin-history-subtle mb-0">{{ $attempt->topic->category->name }}</p>
                                </div>
                                <span class="admin-history-chip {{ $percentage >= 70 ? 'admin-history-chip-success' : 'admin-history-chip-warning' }}">
                                    {{ $attempt->score }} / {{ $attempt->total_questions }} ({{ $percentage }}%)
                                </span>
                            </div>

                            <div class="admin-history-inline-meta">
                                <span>Difficulty: {{ $attempt->topic->difficultyLabel() }}</span>
                                <span>Attempted: {{ $attempt->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
