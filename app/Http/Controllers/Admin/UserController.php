<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search'));
        $selectedRole = trim((string) request('role'));

        $users = User::query()
            ->withCount(['grammarChecks', 'quizAttempts'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when(in_array($selectedRole, ['admin', 'user'], true), function ($query) use ($selectedRole) {
                $query->where('role', $selectedRole);
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'search', 'selectedRole'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function history(User $user)
    {
        $fromDate = trim((string) request('from_date'));
        $toDate = trim((string) request('to_date'));
        $selectedTopicId = trim((string) request('topic_id'));

        $grammarChecksQuery = $user->grammarChecks()->latest();
        $quizAttemptsQuery = $user->quizAttempts()->with('topic.category')->latest();

        if ($fromDate !== '' && $this->isValidDate($fromDate)) {
            $startOfDay = Carbon::parse($fromDate)->startOfDay();
            $grammarChecksQuery->where('created_at', '>=', $startOfDay);
            $quizAttemptsQuery->where('created_at', '>=', $startOfDay);
        }

        if ($toDate !== '' && $this->isValidDate($toDate)) {
            $endOfDay = Carbon::parse($toDate)->endOfDay();
            $grammarChecksQuery->where('created_at', '<=', $endOfDay);
            $quizAttemptsQuery->where('created_at', '<=', $endOfDay);
        }

        if ($selectedTopicId !== '' && ctype_digit($selectedTopicId)) {
            $quizAttemptsQuery->where('topic_id', (int) $selectedTopicId);
        }

        $grammarChecks = $grammarChecksQuery->get();
        $quizAttempts = $quizAttemptsQuery->get();
        $topics = Topic::with('category')->orderBy('name')->get();

        $grammarChecksWithCorrections = $grammarChecks
            ->filter(fn ($check) => $this->grammarCheckHasCorrections($check->report_json ?? null))
            ->count();

        $latestGrammarCheckAt = optional($grammarChecks->first())->created_at;
        $latestQuizAttemptAt = optional($quizAttempts->first())->created_at;
        $latestActivityAt = collect([$latestGrammarCheckAt, $latestQuizAttemptAt])
            ->filter()
            ->sortDesc()
            ->first();

        $averageQuizPercentage = $quizAttempts->isNotEmpty()
            ? round($quizAttempts->avg(function ($attempt) {
                return $attempt->total_questions > 0
                    ? ($attempt->score / $attempt->total_questions) * 100
                    : 0;
            }), 1)
            : null;

        $summary = [
            'grammar_checks_count' => $grammarChecks->count(),
            'grammar_checks_with_corrections_count' => $grammarChecksWithCorrections,
            'quiz_attempts_count' => $quizAttempts->count(),
            'average_quiz_percentage' => $averageQuizPercentage,
            'latest_activity_at' => $latestActivityAt,
        ];

        return view('admin.users.history', compact(
            'user',
            'grammarChecks',
            'quizAttempts',
            'topics',
            'fromDate',
            'toDate',
            'selectedTopicId',
            'summary'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if (Auth::id() === $user->id && $validated['role'] !== 'admin') {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['role' => 'You cannot remove your own admin access.']);
        }

        if (blank($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account from the admin panel.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    protected function grammarCheckHasCorrections(mixed $report): bool
    {
        if (! is_array($report)) {
            return false;
        }

        if (($report['mode'] ?? null) === 'single') {
            return ! ((bool) ($report['is_correct'] ?? false));
        }

        return ! empty($report['issues']) && is_array($report['issues']);
    }

    protected function isValidDate(string $value): bool
    {
        try {
            Carbon::parse($value);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
