<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\GrammarCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GrammarCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_check_grammar_with_typed_text(): void
    {
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [[
                    'content' => [[
                        'text' => json_encode([
                            'mode' => 'single',
                            'is_correct' => false,
                            'original_sentence' => 'She go to school every day.',
                            'corrected_sentence' => 'She goes to school every day.',
                            'summary' => 'The verb should agree with the singular subject "She".',
                            'study_summary' => 'Focus on subject-verb agreement and present simple sentence patterns.',
                            'study_focus' => [
                                [
                                    'topic' => 'Subject-Verb Agreement',
                                    'reason' => 'The verb form does not match the singular subject.',
                                    'priority' => 'high',
                                ],
                            ],
                        ]),
                    ]],
                ]],
            ]),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('grammar.check.store'), [
            'text' => 'She go to school every day.',
        ]);

        $response->assertOk();
        $response->assertSee('She goes to school every day.');
        $response->assertSee('The verb should agree with the singular subject');
        $response->assertSee('What to Study Next');
        $response->assertSee('Subject-Verb Agreement');
        $response->assertSee('BBC Learning English');

        $this->assertDatabaseHas('grammar_checks', [
            'user_id' => $user->id,
            'original_text' => 'She go to school every day.',
            'corrected_text' => 'She goes to school every day.',
        ]);

        $this->assertEquals(
            'grammar-check-result-corrected.txt',
            session('grammar_check_download.filename')
        );
    }

    public function test_user_can_upload_a_text_file_and_download_the_result(): void
    {
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [[
                    'content' => [[
                        'text' => json_encode([
                            'mode' => 'single',
                            'is_correct' => false,
                            'original_sentence' => 'this are the original file content.',
                            'corrected_sentence' => 'This is the corrected file content.',
                            'summary' => 'Capitalization and grammar were improved.',
                            'study_summary' => 'Review subject-verb agreement and sentence-level proofreading.',
                            'study_focus' => [
                                [
                                    'topic' => 'Subject-Verb Agreement',
                                    'reason' => 'The verb form needs to agree with the subject.',
                                    'priority' => 'high',
                                ],
                            ],
                        ]),
                    ]],
                ]],
            ]),
        ]);

        $user = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent(
            'draft.txt',
            'this are the original file content.'
        );

        $response = $this->actingAs($user)->post(route('grammar.check.store'), [
            'document' => $file,
        ]);

        $response->assertOk();
        $response->assertSee('draft.txt');
        $response->assertSee('This is the corrected file content.');

        $downloadResponse = $this->actingAs($user)->get(route('grammar.check.download'));

        $downloadResponse->assertOk();
        $downloadResponse->assertHeader(
            'content-disposition',
            'attachment; filename="draft-corrected.txt"'
        );
        $downloadResponse->assertSee('Corrected Sentence:', false);
        $downloadResponse->assertSee('This is the corrected file content.', false);
        $downloadResponse->assertSee('Explanation:', false);
        $downloadResponse->assertSee('Study Focus:', false);
        $downloadResponse->assertSee('BBC Learning English', false);
    }

    public function test_history_uses_structured_issue_data_when_available(): void
    {
        $user = User::factory()->create();

        GrammarCheck::create([
            'user_id' => $user->id,
            'original_text' => "This are a sample sentence.\nShe go to school every day.",
            'corrected_text' => "Line 1: This is a sample sentence.\nLine 2: She goes to school every day.",
            'explanation' => "Line 1: The verb should agree with the singular subject.\nLine 2: The verb should agree with the singular subject.\n\nOverall comment: The text has basic subject-verb agreement issues.",
            'report_json' => [
                'mode' => 'multi',
                'issues' => [
                    [
                        'reference_label' => 'Line 1',
                        'original' => 'This are a sample sentence.',
                        'corrected' => 'This is a sample sentence.',
                        'explanation' => 'The verb should agree with the singular subject.',
                    ],
                    [
                        'reference_label' => 'Line 2',
                        'original' => 'She go to school every day.',
                        'corrected' => 'She goes to school every day.',
                        'explanation' => 'The verb should agree with the singular subject.',
                    ],
                ],
                'summary' => 'The text has basic subject-verb agreement issues.',
                'study_summary' => 'Keep practicing subject-verb agreement until the correct verb forms feel automatic.',
                'study_focus' => [
                    [
                        'topic' => 'Subject-Verb Agreement',
                        'reason' => 'Both sentences show verb forms that do not match the subject.',
                        'priority' => 'high',
                    ],
                ],
                'study_plan' => [
                    'summary' => 'Keep practicing subject-verb agreement until the correct verb forms feel automatic.',
                    'items' => [
                        [
                            'title' => 'Subject-Verb Agreement',
                            'reason' => 'Both sentences show verb forms that do not match the subject.',
                            'priority' => 'high',
                            'topic' => [
                                'id' => 8,
                                'name' => 'Subject-Verb Agreement',
                                'description' => 'Ensure subjects and verbs match in number and person.',
                                'difficulty' => 'Basic',
                                'category_name' => 'Grammar Rules',
                            ],
                            'resources' => [
                                [
                                    'name' => 'BBC Learning English',
                                    'url' => 'https://www.bbc.co.uk/learningenglish/',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->actingAs($user)->get(route('grammar.history'));

        $response->assertOk();
        $response->assertSee('This are a sample sentence.');
        $response->assertSee('She go to school every day.');
        $response->assertSee('This is a sample sentence.');
        $response->assertSee('She goes to school every day.');
        $response->assertSee('The text has basic subject-verb agreement issues.');
        $response->assertSee('What to Study Next');
        $response->assertSee('BBC Learning English');
    }

    public function test_text_or_document_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(route('grammar.check'))->post(route('grammar.check.store'), []);

        $response->assertRedirect(route('grammar.check'));
        $response->assertSessionHasErrors('text');
    }

    public function test_docx_upload_returns_a_clear_error_when_ziparchive_is_unavailable(): void
    {
        if (class_exists(\ZipArchive::class)) {
            $this->markTestSkipped('ZipArchive is available in this environment.');
        }

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create(
            'draft.docx',
            10,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $response = $this->actingAs($user)
            ->from(route('grammar.check'))
            ->post(route('grammar.check.store'), [
                'document' => $file,
            ]);

        $response->assertRedirect(route('grammar.check'));
        $response->assertSessionHasErrors('document');
    }
}
