<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_tasks_are_paginated_ten_per_page(): void
    {
        foreach (range(1, 11) as $taskNumber) {
            Task::factory()->for($this->user)->create(['title' => "Task {$taskNumber}"]);
        }

        $firstPage = $this->get(route('tasks.index'));
        $secondPage = $this->get(route('tasks.index', ['page' => 2]));

        $firstPage->assertOk();
        $firstPage->assertViewHas('tasks', function ($tasks): bool {
            return $tasks->count() === 10 && $tasks->currentPage() === 1;
        });
        $secondPage->assertViewHas('tasks', function ($tasks): bool {
            return $tasks->count() === 1 && $tasks->currentPage() === 2;
        });
    }
}
