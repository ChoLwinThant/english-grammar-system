<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GrammarCheck;
use App\Models\Topic;

class GrammarCheckController extends Controller
{
    public function index()
    {
        return view('grammar-check');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => ['nullable', 'string', 'max:10000'],
            'document' => ['nullable', 'file', 'max:5120', 'mimes:txt,md,docx,pdf'],
        ]);

        if (! $request->filled('text') && ! $request->hasFile('document')) {
            return back()
                ->withErrors([
                    'text' => 'Please enter text or upload a supported file.',
                ])
                ->withInput();
        }

        $uploadedFile = $request->file('document');
        $sourceType = $uploadedFile ? strtolower($uploadedFile->getClientOriginalExtension()) : 'text';

        if ($uploadedFile && strtolower($uploadedFile->getClientOriginalExtension()) === 'docx' && ! $this->supportsDocxUploads()) {
            return back()
                ->withErrors([
                    'document' => 'DOCX uploads are not available on this server because the PHP zip extension is not enabled. Please upload a TXT or MD file instead.',
                ])
                ->withInput();
        }

        $inputText = $request->filled('text')
            ? trim($request->text)
            : $this->extractTextFromFile($uploadedFile);

        if ($inputText === '') {
            return back()
                ->withErrors([
                    'document' => 'The uploaded file does not contain readable text.',
                ])
                ->withInput();
        }

        $isSingleSentence = $this->isSingleSentenceInput($inputText);
        $lineMappedText = $this->buildLineMappedText($inputText);
        $pdfPageCount = $sourceType === 'pdf' ? $this->detectPdfPageCount($uploadedFile?->getRealPath()) : null;
        $prompt = $this->buildPrompt($inputText, $lineMappedText, $isSingleSentence, $sourceType, $pdfPageCount);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/responses', [
            'model' => env('OPENAI_MODEL', 'gpt-5.4-mini'),
            'input' => $prompt,
        ]);

        if (! $response->successful()) {
            return back()
                ->withErrors([
                    'api' => 'OpenAI request failed: ' . $response->body()
                ])
                ->withInput();
        }

        $data = $response->json();
        $outputText = $data['output'][0]['content'][0]['text'] ?? null;

        $report = $this->parseGrammarResponse($outputText, $isSingleSentence, $sourceType, $pdfPageCount);
        $studyPlan = $this->buildStudyPlan($report);
        $report['study_plan'] = $studyPlan;
        $correctedText = $this->buildCorrectedTextForStorage($report);
        $explanation = $this->buildExplanationForStorage($report);

        GrammarCheck::create([
            'user_id' => Auth::id(),
            'original_text' => $inputText,
            'corrected_text' => $correctedText,
            'explanation' => $explanation,
            'report_json' => $report,
        ]);

        $downloadFilename = $this->buildDownloadFilename($uploadedFile?->getClientOriginalName());

        $request->session()->put('grammar_check_download', [
            'filename' => $downloadFilename,
            'content' => $this->buildDownloadContent($report),
        ]);

        return view('grammar-check', [
            'originalText' => $inputText,
            'correctedText' => $correctedText,
            'explanation' => $explanation ?: 'No explanation returned.',
            'sourceLabel' => $uploadedFile ? $uploadedFile->getClientOriginalName() : 'Typed text',
            'downloadReady' => true,
            'report' => $report,
            'studyPlan' => $studyPlan,
        ]);
    }

    public function download(Request $request)
    {
        $download = $request->session()->get('grammar_check_download');

        abort_unless($download, 404);

        return response($download['content'])
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $download['filename'] . '"');
    }

    public function history()
    {
        $grammarChecks = GrammarCheck::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('grammar-history', compact('grammarChecks'));
    }

    protected function extractTextFromFile($file): string
    {
        if (! $file) {
            return '';
        }

        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'txt', 'md' => trim((string) file_get_contents($file->getRealPath())),
            'docx' => $this->extractTextFromDocx($file->getRealPath()),
            'pdf' => $this->extractTextFromPdf($file->getRealPath()),
            default => '',
        };
    }

    protected function extractTextFromDocx(string $path): string
    {
        if (! $this->supportsDocxUploads()) {
            return '';
        }

        $zip = new \ZipArchive();

        if ($zip->open($path) !== true) {
            return '';
        }

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (! $documentXml) {
            return '';
        }

        $documentXml = str_replace(['</w:p>', '</w:tr>', '</w:tc>'], "\n", $documentXml);
        $documentXml = str_replace('</w:t>', ' ', $documentXml);
        $text = strip_tags($documentXml);

        return trim(preg_replace("/\R{2,}/", "\n\n", html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8')));
    }

    protected function extractTextFromPdf(string $path): string
    {
        $content = @file_get_contents($path);

        if ($content === false || $content === '') {
            return '';
        }

        preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $content, $matches);

        if (empty($matches[1])) {
            return '';
        }

        $segments = [];

        foreach ($matches[1] as $stream) {
            $decoded = $this->decodePdfStream($stream);

            if ($decoded === '') {
                continue;
            }

            $text = $this->extractPdfTextOperators($decoded);

            if ($text !== '') {
                $segments[] = $text;
            }
        }

        return trim(preg_replace("/\R{3,}/", "\n\n", implode("\n", $segments)));
    }

    protected function detectPdfPageCount(?string $path): ?int
    {
        if (! $path) {
            return null;
        }

        $content = @file_get_contents($path);

        if ($content === false || $content === '') {
            return null;
        }

        if (preg_match('/\/Count\s+(\d+)/', $content, $matches)) {
            return max(1, (int) $matches[1]);
        }

        preg_match_all('/\/Type\s*\/Page\b/', $content, $matches);

        return ! empty($matches[0]) ? count($matches[0]) : null;
    }

    protected function decodePdfStream(string $stream): string
    {
        $stream = ltrim($stream, "\r\n");

        $decoded = @gzuncompress($stream);

        if ($decoded !== false) {
            return $decoded;
        }

        $decoded = @gzinflate($stream);

        if ($decoded !== false) {
            return $decoded;
        }

        $decoded = @gzdecode($stream);

        if ($decoded !== false) {
            return $decoded;
        }

        return preg_match('/BT|Tj|TJ/', $stream) ? $stream : '';
    }

    protected function extractPdfTextOperators(string $content): string
    {
        $result = '';

        if (preg_match_all('/\((?:\\\\.|[^()\\\\])*\)\s*Tj/s', $content, $matches)) {
            foreach ($matches[0] as $match) {
                if (preg_match('/\(((?:\\\\.|[^()\\\\])*)\)\s*Tj/s', $match, $stringMatch)) {
                    $result .= $this->decodePdfString($stringMatch[1]);
                }
            }
        }

        if (preg_match_all('/\[(.*?)\]\s*TJ/s', $content, $matches)) {
            foreach ($matches[1] as $arrayContent) {
                if (preg_match_all('/\(((?:\\\\.|[^()\\\\])*)\)/s', $arrayContent, $stringMatches)) {
                    foreach ($stringMatches[1] as $pdfString) {
                        $result .= $this->decodePdfString($pdfString);
                    }
                }
            }
        }

        $result = preg_replace('/\s+/', ' ', $result ?? '');

        return trim($result);
    }

    protected function decodePdfString(string $value): string
    {
        $value = preg_replace_callback('/\\\\([0-7]{1,3})/', function ($matches) {
            return chr(octdec($matches[1]));
        }, $value);

        $replacements = [
            '\\\\n' => "\n",
            '\\\\r' => "\r",
            '\\\\t' => "\t",
            '\\\\b' => "\x08",
            '\\\\f' => "\f",
            '\\\\(' => '(',
            '\\\\)' => ')',
            '\\\\\\\\' => '\\',
        ];

        return strtr($value, $replacements);
    }

    protected function buildDownloadFilename(?string $originalFilename): string
    {
        $baseName = $originalFilename
            ? pathinfo($originalFilename, PATHINFO_FILENAME)
            : 'grammar-check-result';

        $slug = Str::slug($baseName);

        return ($slug !== '' ? $slug : 'grammar-check-result') . '-corrected.txt';
    }

    protected function buildDownloadContent(array $report): string
    {
        if (($report['mode'] ?? 'single') === 'single') {
            return "Status: " . ($report['is_correct'] ? 'Correct' : 'Needs correction') . "\n"
                . "Sentence:\n" . ($report['original_sentence'] ?? '') . "\n\n"
                . "Corrected Sentence:\n" . ($report['corrected_sentence'] ?? '') . "\n\n"
                . "Explanation:\n" . ($report['summary'] ?? '') . "\n"
                . $this->buildStudyPlanDownloadContent($report);
        }

        $content = "Issue Summary\n";

        if (empty($report['issues'])) {
            return $content
                . "No incorrect sentences found.\n\n"
                . "Comment:\n" . ($report['summary'] ?? '') . "\n"
                . $this->buildStudyPlanDownloadContent($report);
        }

        foreach ($report['issues'] as $index => $issue) {
            $content .= "\nIssue " . ($index + 1) . "\n";
            $content .= "Reference: " . ($issue['reference_label'] ?: 'Snippet only') . "\n";
            $content .= "Original: " . ($issue['original'] ?: 'Not provided') . "\n";
            $content .= "Corrected: " . ($issue['corrected'] ?: 'Not provided') . "\n";
            $content .= "Explanation: " . ($issue['explanation'] ?: 'Not provided') . "\n";
        }

        if (! empty($report['summary'])) {
            $content .= "\nOverall Comment:\n" . $report['summary'] . "\n";
        }

        return $content . $this->buildStudyPlanDownloadContent($report);
    }

    protected function supportsDocxUploads(): bool
    {
        return class_exists(\ZipArchive::class);
    }

    protected function isSingleSentenceInput(string $text): bool
    {
        $normalized = trim(preg_replace('/\s+/', ' ', $text));

        if ($normalized === '') {
            return true;
        }

        if (preg_match('/[\r\n]/', $text)) {
            return false;
        }

        preg_match_all('/[.!?]+(?=\s|$)/', $normalized, $matches);

        return count($matches[0]) <= 1;
    }

    protected function buildLineMappedText(string $text): string
    {
        $lines = preg_split("/\R/", $text) ?: [];
        $mapped = [];

        foreach ($lines as $index => $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                continue;
            }

            $mapped[] = '[Line ' . ($index + 1) . '] ' . $trimmed;
        }

        return implode("\n", $mapped);
    }

    protected function buildPrompt(
        string $inputText,
        string $lineMappedText,
        bool $isSingleSentence,
        string $sourceType,
        ?int $pdfPageCount
    ): string
    {
        if ($isSingleSentence) {
            return <<<PROMPT
            You are an English grammar tutor.

            Review the single sentence below.
            Decide whether it is already grammatically correct.
            If it is correct, keep the corrected sentence the same and briefly explain why it is correct.
            If it is incorrect, provide the corrected sentence and a short explanation of the mistakes.

            Return ONLY valid JSON in this exact structure:
            {
              "mode": "single",
              "is_correct": true,
              "original_sentence": "...",
              "corrected_sentence": "...",
              "summary": "...",
              "study_summary": "...",
              "study_focus": [
                {
                  "topic": "...",
                  "reason": "...",
                  "priority": "high"
                }
              ]
            }

            Sentence:
            {$inputText}
            PROMPT;
        }

        if ($sourceType === 'pdf') {
            $referenceInstruction = $pdfPageCount && $pdfPageCount > 1
                ? 'This PDF has multiple pages. For each issue, set "reference_label" to "Page X" only when the page can be identified confidently from the extracted text. Otherwise leave it empty. Never invent page numbers.'
                : 'This PDF is a single-page document or its exact page mapping is unavailable. Leave "reference_label" empty and rely on the original snippet instead.';

            return <<<PROMPT
            You are an English grammar tutor.

            Review the PDF text below.
            Only report sentences or snippets that contain grammar, spelling, punctuation, or wording mistakes.
            Do not rewrite or repeat the entire text.
            {$referenceInstruction}
            Use the "original" field as the main reference snippet that helps the user locate the issue.
            If there are no incorrect sentences, return an empty issues array and provide a brief summary explaining why the grammar is correct and what structures are used well.

            Return ONLY valid JSON in this exact structure:
            {
              "mode": "multi",
              "issues": [
                {
                  "reference_label": "",
                  "original": "...",
                  "corrected": "...",
                  "explanation": "..."
                }
              ],
              "summary": "...",
              "study_summary": "...",
              "study_focus": [
                {
                  "topic": "...",
                  "reason": "...",
                  "priority": "high"
                }
              ]
            }

            PDF text:
            {$inputText}
            PROMPT;
        }

        return <<<PROMPT
        You are an English grammar tutor.

        Review the text below.
        Only report sentences or lines that contain grammar, spelling, punctuation, or wording mistakes.
        Do not rewrite or repeat the entire text.
        Use the provided line numbers when you can identify where the issue appears.
        If there are no incorrect sentences, return an empty issues array and provide a brief summary explaining why the grammar is correct and what structures are used well.

        Return ONLY valid JSON in this exact structure:
        {
          "mode": "multi",
          "issues": [
            {
              "reference_label": "Line 3",
              "original": "...",
              "corrected": "...",
              "explanation": "..."
            }
          ],
          "summary": "...",
          "study_summary": "...",
          "study_focus": [
            {
              "topic": "...",
              "reason": "...",
              "priority": "high"
            }
          ]
        }

        Text with line references:
        {$lineMappedText}
        PROMPT;
    }

    protected function parseGrammarResponse(?string $outputText, bool $isSingleSentence, string $sourceType, ?int $pdfPageCount): array
    {
        $fallback = $isSingleSentence
            ? [
                'mode' => 'single',
                'is_correct' => false,
                'original_sentence' => '',
                'corrected_sentence' => 'Error',
                'summary' => 'Unable to parse the grammar check response.',
                'study_summary' => '',
                'study_focus' => [],
            ]
            : [
                'mode' => 'multi',
                'issues' => [],
                'summary' => 'Unable to parse the grammar check response.',
                'study_summary' => '',
                'study_focus' => [],
            ];

        if (! $outputText) {
            return $fallback;
        }

        $json = json_decode($outputText, true);

        if (! is_array($json)) {
            return $fallback;
        }

        if (($json['mode'] ?? null) === 'single' || $isSingleSentence) {
            return [
                'mode' => 'single',
                'is_correct' => (bool) ($json['is_correct'] ?? false),
                'original_sentence' => trim((string) ($json['original_sentence'] ?? '')),
                'corrected_sentence' => trim((string) ($json['corrected_sentence'] ?? '')),
                'summary' => trim((string) ($json['summary'] ?? '')),
                'study_summary' => trim((string) ($json['study_summary'] ?? '')),
                'study_focus' => $this->normalizeStudyFocus($json['study_focus'] ?? []),
            ];
        }

        $issues = [];

        foreach (($json['issues'] ?? []) as $issue) {
            if (! is_array($issue)) {
                continue;
            }

            $issues[] = [
                'reference_label' => $this->normalizeReferenceLabel(
                    trim((string) ($issue['reference_label'] ?? $issue['line_reference'] ?? '')),
                    $sourceType,
                    $pdfPageCount
                ),
                'original' => trim((string) ($issue['original'] ?? '')),
                'corrected' => trim((string) ($issue['corrected'] ?? '')),
                'explanation' => trim((string) ($issue['explanation'] ?? '')),
            ];
        }

        return [
            'mode' => 'multi',
            'issues' => $issues,
            'summary' => trim((string) ($json['summary'] ?? '')),
            'study_summary' => trim((string) ($json['study_summary'] ?? '')),
            'study_focus' => $this->normalizeStudyFocus($json['study_focus'] ?? []),
        ];
    }

    protected function buildCorrectedTextForStorage(array $report): string
    {
        if (($report['mode'] ?? 'single') === 'single') {
            return $report['corrected_sentence'] ?: 'Error';
        }

        if (empty($report['issues'])) {
            return 'No corrections needed.';
        }

        return collect($report['issues'])
            ->map(function (array $issue) {
                $reference = $issue['reference_label'] ?: 'Snippet only';

                return $reference . ': ' . ($issue['corrected'] ?: 'No corrected sentence returned.');
            })
            ->implode("\n");
    }

    protected function buildExplanationForStorage(array $report): string
    {
        if (($report['mode'] ?? 'single') === 'single') {
            return $report['summary'] ?: 'No explanation returned.';
        }

        if (empty($report['issues'])) {
            return $report['summary'] ?: 'No incorrect sentences were found.';
        }

        $issueText = collect($report['issues'])
            ->map(function (array $issue) {
                $reference = $issue['reference_label'] ?: 'Snippet only';

                return $reference . ': ' . ($issue['explanation'] ?: 'No explanation returned.');
            })
            ->implode("\n");

        if (! empty($report['summary'])) {
            $issueText .= "\n\nOverall comment: " . $report['summary'];
        }

        return $issueText;
    }

    protected function normalizeReferenceLabel(string $label, string $sourceType, ?int $pdfPageCount): string
    {
        if ($sourceType !== 'pdf') {
            return $label;
        }

        $normalized = trim($label);

        if ($normalized === '') {
            return '';
        }

        if ($pdfPageCount === 1) {
            return '';
        }

        if (preg_match('/^page\s+\d+$/i', $normalized)) {
            return preg_replace('/\s+/', ' ', ucwords(strtolower($normalized))) ?? '';
        }

        return '';
    }

    protected function normalizeStudyFocus($items): array
    {
        $focusItems = [];

        foreach ((array) $items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $topic = trim((string) ($item['topic'] ?? ''));
            $reason = trim((string) ($item['reason'] ?? ''));
            $priority = strtolower(trim((string) ($item['priority'] ?? 'medium')));

            if ($topic === '' && $reason === '') {
                continue;
            }

            if (! in_array($priority, ['high', 'medium', 'low'], true)) {
                $priority = 'medium';
            }

            $focusItems[] = [
                'topic' => $topic,
                'reason' => $reason,
                'priority' => $priority,
            ];
        }

        return collect($focusItems)
            ->unique(function (array $item) {
                return Str::lower($item['topic'] . '|' . $item['reason']);
            })
            ->values()
            ->all();
    }

    protected function buildStudyPlan(array $report): array
    {
        $topics = Topic::with('category')->get();
        $focusItems = collect($report['study_focus'] ?? []);

        if ($focusItems->isEmpty()) {
            $focusItems = $this->deriveStudyFocusFromReport($report);
        }

        $items = $focusItems
            ->map(function (array $focus) use ($topics) {
                $matchedTopic = $this->matchTopicFromFocus($focus, $topics);

                return [
                    'title' => $matchedTopic?->name ?: ($focus['topic'] ?: 'Grammar accuracy'),
                    'reason' => $focus['reason'] ?: 'More targeted practice will help make this grammar area more automatic.',
                    'priority' => $focus['priority'] ?? 'medium',
                    'topic' => $matchedTopic ? [
                        'id' => $matchedTopic->id,
                        'name' => $matchedTopic->name,
                        'description' => $matchedTopic->description,
                        'difficulty' => $matchedTopic->difficultyLabel(),
                        'category_name' => $matchedTopic->category?->name,
                    ] : null,
                    'resources' => $this->buildExternalResources($matchedTopic?->name ?: $focus['topic']),
                ];
            })
            ->unique(function (array $item) {
                return Str::lower($item['title']);
            })
            ->take(3)
            ->values()
            ->all();

        $summary = trim((string) ($report['study_summary'] ?? ''));

        if ($summary === '') {
            $summary = empty($items)
                ? 'Your writing looks strong overall. Keep reviewing a mix of grammar topics to stay consistent.'
                : 'Focus on the topics below, then try a short quiz or practice activity to reinforce the patterns from this grammar check.';
        }

        return [
            'summary' => $summary,
            'items' => $items,
        ];
    }

    protected function deriveStudyFocusFromReport(array $report)
    {
        $signals = [];

        if (($report['mode'] ?? 'single') === 'single') {
            $signals[] = implode(' ', [
                $report['original_sentence'] ?? '',
                $report['corrected_sentence'] ?? '',
                $report['summary'] ?? '',
            ]);
        } else {
            foreach ($report['issues'] ?? [] as $issue) {
                $signals[] = implode(' ', [
                    $issue['original'] ?? '',
                    $issue['corrected'] ?? '',
                    $issue['explanation'] ?? '',
                ]);
            }

            $signals[] = $report['summary'] ?? '';
        }

        $combinedSignals = Str::lower(implode(' ', $signals));

        $rules = [
            'Subject-Verb Agreement' => ['subject-verb', 'subject verb', 'verb agree', 'singular subject', 'plural subject', 'third-person singular'],
            'Articles' => ['article', 'articles', 'a an the'],
            'Present Simple' => ['present simple', 'simple present', 'routine', 'habit'],
            'Past Simple' => ['past simple', 'simple past', 'past tense'],
            'Future Simple' => ['future simple', 'future tense', 'will ', 'going to'],
            'Prepositions of Time' => ['preposition of time', 'prepositions of time', 'at on in time'],
            'Prepositions of Place' => ['preposition of place', 'prepositions of place', 'location preposition'],
            'Comparative Adjectives' => ['comparative', 'more than', 'than'],
            'Superlative Adjectives' => ['superlative', 'the most', 'the best', 'the biggest'],
            'Modal Verbs' => ['modal', 'should', 'must', 'can ', 'could ', 'may ', 'might '],
        ];

        $matches = collect($rules)
            ->filter(function (array $keywords) use ($combinedSignals) {
                foreach ($keywords as $keyword) {
                    if (str_contains($combinedSignals, Str::lower($keyword))) {
                        return true;
                    }
                }

                return false;
            })
            ->keys()
            ->values()
            ->map(function (string $topicName) {
                return [
                    'topic' => $topicName,
                    'reason' => 'This topic appeared in the grammar patterns identified in your writing.',
                    'priority' => 'high',
                ];
            });

        if ($matches->isNotEmpty()) {
            return $matches;
        }

        return collect([
            [
                'topic' => 'Grammar Rules',
                'reason' => 'Reviewing core grammar patterns will help you keep your writing accurate and consistent.',
                'priority' => 'medium',
            ],
        ]);
    }

    protected function matchTopicFromFocus(array $focus, $topics): ?Topic
    {
        $signal = Str::lower(trim(($focus['topic'] ?? '') . ' ' . ($focus['reason'] ?? '')));

        $aliases = [
            'Present Simple' => ['present simple', 'simple present', 'habit', 'routine'],
            'Past Simple' => ['past simple', 'simple past', 'past tense'],
            'Future Simple' => ['future simple', 'future tense', 'will', 'going to'],
            'Prepositions of Time' => ['prepositions of time', 'preposition of time', 'time expressions'],
            'Prepositions of Place' => ['prepositions of place', 'preposition of place', 'location'],
            'Comparative Adjectives' => ['comparative', 'compare two'],
            'Superlative Adjectives' => ['superlative', 'highest degree'],
            'Subject-Verb Agreement' => ['subject-verb agreement', 'subject verb agreement', 'verb agreement', 'singular subject', 'plural subject'],
            'Articles' => ['article', 'articles', 'a an the'],
            'Modal Verbs' => ['modal verb', 'modal verbs', 'should', 'must', 'can', 'could', 'may', 'might'],
        ];

        foreach ($topics as $topic) {
            $topicName = Str::lower($topic->name);

            if ($topicName !== '' && str_contains($signal, $topicName)) {
                return $topic;
            }

            foreach ($aliases[$topic->name] ?? [] as $alias) {
                if (str_contains($signal, Str::lower($alias))) {
                    return $topic;
                }
            }
        }

        return null;
    }

    protected function buildExternalResources(?string $topicName): array
    {
        $defaultResources = [
            [
                'name' => 'BBC Learning English',
                'url' => 'https://www.bbc.co.uk/learningenglish/',
            ],
            [
                'name' => 'British Council LearnEnglish Grammar',
                'url' => 'https://learnenglish.britishcouncil.org/grammar',
            ],
            [
                'name' => 'Cambridge Dictionary Grammar',
                'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
            ],
        ];

        $topicResources = [
            'Subject-Verb Agreement' => [
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
            ],
            'Articles' => [
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
            ],
            'Present Simple' => [
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
            ],
            'Past Simple' => [
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
            ],
            'Future Simple' => [
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
            ],
            'Prepositions of Time' => [
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
            ],
            'Prepositions of Place' => [
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
            ],
            'Comparative Adjectives' => [
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
            ],
            'Superlative Adjectives' => [
                [
                    'name' => 'BBC Learning English',
                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                ],
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
            ],
            'Modal Verbs' => [
                [
                    'name' => 'British Council Grammar',
                    'url' => 'https://learnenglish.britishcouncil.org/grammar',
                ],
                [
                    'name' => 'Cambridge Dictionary Grammar',
                    'url' => 'https://dictionary.cambridge.org/grammar/british-grammar',
                ],
            ],
        ];

        return $topicResources[$topicName] ?? $defaultResources;
    }

    protected function buildStudyPlanDownloadContent(array $report): string
    {
        $studyPlan = $report['study_plan'] ?? $this->buildStudyPlan($report);

        if (empty($studyPlan['items'])) {
            return '';
        }

        $content = "\nStudy Focus:\n";

        if (! empty($studyPlan['summary'])) {
            $content .= $studyPlan['summary'] . "\n";
        }

        foreach ($studyPlan['items'] as $index => $item) {
            $content .= "\n" . ($index + 1) . '. ' . ($item['title'] ?? 'Grammar practice') . "\n";
            $content .= 'Reason: ' . ($item['reason'] ?? 'Targeted review will help reinforce this area.') . "\n";

            foreach (($item['resources'] ?? []) as $resource) {
                $content .= 'Resource: ' . ($resource['name'] ?? 'Online resource') . ' - ' . ($resource['url'] ?? '') . "\n";
            }
        }

        return $content;
    }
}
