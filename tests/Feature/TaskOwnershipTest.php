<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_users_index_only_shows_their_own_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $ownTask = Task::factory()->for($userA)->create(['title' => 'User A task']);
        $otherTask = Task::factory()->for($userB)->create(['title' => 'User B task']);

        $response = $this->actingAs($userA)->get(route('tasks.index'));

        $response->assertSee('User A task');
        $response->assertDontSee('User B task');
    }

    public function test_a_user_cannot_view_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->get(route('tasks.show', $task));

        $response->assertNotFound();
    }

    public function test_a_user_cannot_edit_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->get(route('tasks.edit', $task));

        $response->assertNotFound();
    }

    public function test_a_user_cannot_update_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create(['title' => 'Original title']);

        $response = $this->actingAs($intruder)->put(route('tasks.update', $task), [
            'title' => 'Hijacked title',
        ]);

        $response->assertNotFound();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Original title',
        ]);
    }

    public function test_a_user_cannot_delete_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $response = $this->actingAs($intruder)->delete(route('tasks.destroy', $task));

        $response->assertNotFound();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_a_newly_created_task_belongs_to_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'My new task',
            'description' => 'Some description',
            'submission_date' => '2026-09-01',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'My new task',
            'user_id' => $user->id,
        ]);
    }
}
