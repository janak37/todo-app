<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_are_paginated_ten_per_page(): void
    {
        foreach (range(1, 11) as $taskNumber) {
            Task::create(['title' => "Task {$taskNumber}"]);
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