<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireAuthAndTaskFormsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_fails_without_a_title(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('pages::tasks.create')
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_create_task_succeeds_with_valid_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('pages::tasks.create')
            ->set('title', 'New task from Livewire test')
            ->set('description', 'Some description')
            ->call('save')
            ->assertRedirect(route('livewire.tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'title' => 'New task from Livewire test',
            'user_id' => $user->id,
        ]);
    }

    public function test_a_user_cannot_edit_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder);

        Livewire::test('pages::tasks.edit', ['task' => $task])
            ->assertNotFound();
    }

    public function test_a_user_cannot_view_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder);

        Livewire::test('pages::tasks.show', ['task' => $task])
            ->assertNotFound();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        Livewire::test('pages::auth.login')
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_succeeds_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        Livewire::test('pages::auth.login')
            ->set('email', $user->email)
            ->set('password', 'correct-password')
            ->call('login')
            ->assertRedirect(route('livewire.tasks.index'));

        $this->assertAuthenticatedAs($user);
    }
}