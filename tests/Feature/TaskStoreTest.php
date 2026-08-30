<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
{
    parent::setUp();
    $this->actingAs(User::factory()->create());
}

    public function test_a_task_can_be_created_with_valid_data(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, bread',
            'submission_date' => '2026-09-01',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, bread',
        ]);
    }

    public function test_a_task_cannot_be_created_without_a_title(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => '',
            'description' => 'This should fail validation',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('tasks', [
            'description' => 'This should fail validation',
        ]);
    }
    


}