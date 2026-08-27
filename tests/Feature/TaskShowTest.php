<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_existing_task_can_be_viewed(): void
    {
        $task = Task::factory()->create([
            'title' => 'Finish the report',
        ]);

        $response = $this->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertSee('Finish the report');
    }

    public function test_visiting_a_nonexistent_task_returns_a_404(): void
    {
        $response = $this->get('/tasks/99999');

        $response->assertNotFound();
    }
}