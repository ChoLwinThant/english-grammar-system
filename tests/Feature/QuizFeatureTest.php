<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_quiz_page(): void
    {
        $response = $this->get('/quiz');

        $response->assertRedirect('/login');
    }

    public function test_logged_in_user_can_view_quiz_categories(): void
    {
        $user = User::factory()->create();

        Category::create([
            'name' => 'Tenses',
            'description' => 'Practice grammar tenses.',
        ]);

        $response = $this->actingAs($user)->get('/quiz');

        $response->assertStatus(200);
        $response->assertSee('Tenses');
    }

    public function test_logged_in_user_can_view_topics_by_category(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Tenses',
            'description' => 'Practice grammar tenses.',
        ]);

        Topic::create([
            'category_id' => $category->id,
            'name' => 'Present Simple',
            'description' => 'Practice present simple tense.',
            'difficulty' => 'basic',
        ]);

        $response = $this->actingAs($user)->get('/quiz/category/' . $category->id);

        $response->assertStatus(200);
        $response->assertSee('Present Simple');
    }

    public function test_logged_in_user_can_start_quiz_for_topic(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Tenses',
            'description' => 'Practice grammar tenses.',
        ]);

        $topic = Topic::create([
            'category_id' => $category->id,
            'name' => 'Present Simple',
            'description' => 'Practice present simple tense.',
            'difficulty' => 'basic',
        ]);

        Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'She ___ to school every day.',
            'option_a' => 'go',
            'option_b' => 'goes',
            'option_c' => 'going',
            'option_d' => 'gone',
            'correct_answer' => 'B',
            'explanation' => 'Use "goes" because the subject "She" is third person singular.',
        ]);

        $response = $this->actingAs($user)->get('/quiz/topic/' . $topic->id);

        $response->assertStatus(200);
        $response->assertSee('She ___ to school every day.');
    }

    public function test_user_can_submit_quiz_and_score_is_saved(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Tenses',
            'description' => 'Practice grammar tenses.',
        ]);

        $topic = Topic::create([
            'category_id' => $category->id,
            'name' => 'Present Simple',
            'description' => 'Practice present simple tense.',
            'difficulty' => 'basic',
        ]);

        $questionOne = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'She ___ to school every day.',
            'option_a' => 'go',
            'option_b' => 'goes',
            'option_c' => 'going',
            'option_d' => 'gone',
            'correct_answer' => 'B',
            'explanation' => 'Use "goes" because the subject "She" is third person singular.',
        ]);

        $questionTwo = Question::create([
            'topic_id' => $topic->id,
            'question_text' => 'They ___ football on Sundays.',
            'option_a' => 'play',
            'option_b' => 'plays',
            'option_c' => 'playing',
            'option_d' => 'played',
            'correct_answer' => 'A',
            'explanation' => 'Use the base verb with plural subjects.',
        ]);

        $response = $this->actingAs($user)->post('/quiz/topic/' . $topic->id, [
            'question_' . $questionOne->id => 'B',
            'question_' . $questionTwo->id => 'C',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $user->id,
            'topic_id' => $topic->id,
            'score' => 1,
            'total_questions' => 2,
        ]);
    }

    public function test_user_can_view_own_quiz_history(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'Tenses',
            'description' => 'Practice grammar tenses.',
        ]);

        $topic = Topic::create([
            'category_id' => $category->id,
            'name' => 'Present Simple',
            'description' => 'Practice present simple tense.',
            'difficulty' => 'basic',
        ]);

        \App\Models\QuizAttempt::create([
            'user_id' => $user->id,
            'topic_id' => $topic->id,
            'score' => 1,
            'total_questions' => 2,
        ]);

        $response = $this->actingAs($user)->get('/quiz-history');

        $response->assertStatus(200);
        $response->assertSee('Present Simple');
    }
}
