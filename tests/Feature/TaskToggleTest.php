<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskToggleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_toggling_an_incomplete_task_marks_it_completed(): void
    {
        $task = Task::factory()->for($this->user)->create(['is_completed' => false]);

        $response = $this->patch(route('tasks.toggle', $task));

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_completed' => true,
        ]);
    }

    public function test_toggling_a_completed_task_marks_it_incomplete(): void
    {
        $task = Task::factory()->for($this->user)->create(['is_completed' => true]);

        $response = $this->patch(route('tasks.toggle', $task));

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_completed' => false,
        ]);
    }
}