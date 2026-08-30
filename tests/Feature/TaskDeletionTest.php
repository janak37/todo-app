<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeletionTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
{
    parent::setUp();
    $this->actingAs(User::factory()->create());
}

    public function test_deleting_a_task_hides_it_without_removing_the_database_row(): void
    {
        $task = Task::create([
            'title' => 'Keep this record',
            'description' => 'This task should be recoverable.',
        ]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
        $this->assertNotNull(Task::withTrashed()->find($task->id));
    }
  


}