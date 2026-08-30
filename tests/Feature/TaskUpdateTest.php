<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_can_be_updated_with_valid_data(): void
    {
        $task = Task::factory()->create([
            'title' => 'Old title',
            'description' => 'Old description',
        ]);

        $response = $this->put(route('tasks.update', $task), [
        'title' => 'New title',
        'description' => 'New description',
        'submission_date' => '2026-09-01',
]);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'New title',
            'description' => 'New description',
        ]);
    }

    public function test_a_task_cannot_be_updated_without_a_title(): void
    {
        $task = Task::factory()->create([
            'title' => 'Original title',
        ]);

        $response = $this->put(route('tasks.update', $task), [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Original title',
        ]);
    }
}