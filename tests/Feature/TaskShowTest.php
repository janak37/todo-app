<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_an_existing_task_can_be_viewed(): void
    {
        $task = Task::factory()->for($this->user)->create([
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
