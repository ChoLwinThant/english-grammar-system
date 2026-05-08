<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GrammarCorrectionService
{
    public function correct(string $inputText, string $sourceType = 'text', ?int $pdfPageCount = null): array
    {
        $isSingleSentence = $this->isSingleSentenceInput($inputText);
        $lineMappedText = $this->buildLineMappedText($inputText);
        $prompt = $this->buildPrompt($inputText, $lineMappedText, $isSingleSentence, $sourceType, $pdfPageCount);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/responses', [
            'model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
            'input' => $prompt,
        ]);

        if (! $response->successful()) {
            return [
                'error' => 'OpenAI request failed: ' . $response->body(),
            ];
        }

        $data = $response->json();
        $outputText = $data['output'][0]['content'][0]['text'] ?? null;
        $report = $this->parseGrammarResponse($outputText, $isSingleSentence, $sourceType, $pdfPageCount);

        if ($report === null) {
            return [
                'error' => 'AI response could not be processed. Please try again.',
            ];
        }

        if (! $this->hasCompleteGrammarResponse($report)) {
            return [
                'error' => 'AI response was incomplete. Please try again.',
            ];
        }

        return [
            'report' => $report,
        ];
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
    ): string {
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

    protected function parseGrammarResponse(?string $outputText, bool $isSingleSentence, string $sourceType, ?int $pdfPageCount): ?array
    {
        if (! $outputText) {
            return null;
        }

        $json = json_decode($outputText, true);

        if (! is_array($json)) {
            return null;
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

    protected function hasCompleteGrammarResponse(array $report): bool
    {
        if (($report['mode'] ?? 'single') === 'single') {
            return trim((string) ($report['corrected_sentence'] ?? '')) !== ''
                && trim((string) ($report['summary'] ?? '')) !== '';
        }

        if (! empty($report['issues'])) {
            foreach ($report['issues'] as $issue) {
                if (
                    trim((string) ($issue['original'] ?? '')) === ''
                    || trim((string) ($issue['corrected'] ?? '')) === ''
                    || trim((string) ($issue['explanation'] ?? '')) === ''
                ) {
                    return false;
                }
            }

            return true;
        }

        return trim((string) ($report['summary'] ?? '')) !== '';
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
}
