<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\Category;
use App\Models\GrammarCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee($member->email);
    }

    public function test_non_admin_cannot_access_user_management_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Member',
            'email' => 'member@example.com',
            'role' => 'user',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'member@example.com',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'user']);
        $originalPassword = $member->password;

        $response = $this->actingAs($admin)->put(route('admin.users.update', $member), [
            'name' => 'Updated Member',
            'email' => $member->email,
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $member->refresh();

        $this->assertSame('Updated Member', $member->name);
        $this->assertSame('admin', $member->role);
        $this->assertSame($originalPassword, $member->password);
    }

    public function test_admin_cannot_remove_their_own_admin_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->from(route('admin.users.edit', $admin))->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.users.edit', $admin));
        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_cannot_delete_their_own_account_from_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_can_view_a_users_history_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Tenses']);
        $topic = Topic::create([
            'category_id' => $category->id,
            'name' => 'Present Simple',
            'difficulty' => 'basic',
        ]);

        GrammarCheck::create([
            'user_id' => $member->id,
            'original_text' => 'She go to school.',
            'corrected_text' => 'She goes to school.',
            'explanation' => 'Verb agreement correction.',
            'report_json' => [
                'mode' => 'single',
                'is_correct' => false,
            ],
        ]);

        QuizAttempt::create([
            'user_id' => $member->id,
            'topic_id' => $topic->id,
            'score' => 4,
            'total_questions' => 5,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.history', $member));

        $response->assertOk();
        $response->assertSee($member->email);
        $response->assertSee('Present Simple');
    }

    public function test_non_admin_cannot_view_a_users_history_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $member = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.users.history', $member));

        $response->assertForbidden();
    }
}
