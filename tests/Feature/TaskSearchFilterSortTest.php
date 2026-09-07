<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSearchFilterSortTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_search_finds_tasks_matching_title_or_description(): void
    {
        Task::factory()->for($this->user)->create([
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, bread',
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Clean the house',
            'description' => 'Focus on the kitchen',
        ]);

        $response = $this->get(route('tasks.index', ['q' => 'kitchen']));

        $response->assertSee('Clean the house');
        $response->assertDontSee('Buy groceries');
    }

    public function test_search_ignores_tasks_that_do_not_match(): void
    {
        Task::factory()->for($this->user)->create(['title' => 'Write report']);

        $response = $this->get(route('tasks.index', ['q' => 'nonexistentterm']));

        $response->assertDontSee('Write report');
        $response->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 0);
    }

    public function test_status_filter_shows_only_active_tasks(): void
    {
        Task::factory()->for($this->user)->create([
            'title' => 'Active task',
            'is_completed' => false,
            'submission_date' => now()->addDays(3),
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Completed task',
            'is_completed' => true,
        ]);

        $response = $this->get(route('tasks.index', ['status' => 'active']));

        $response->assertSee('Active task');
        $response->assertDontSee('Completed task');
    }

    public function test_status_filter_shows_only_completed_tasks(): void
    {
        Task::factory()->for($this->user)->create([
            'title' => 'Active task',
            'is_completed' => false,
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Completed task',
            'is_completed' => true,
        ]);

        $response = $this->get(route('tasks.index', ['status' => 'completed']));

        $response->assertSee('Completed task');
        $response->assertDontSee('Active task');
    }

    public function test_status_filter_shows_only_overdue_tasks(): void
    {
        Task::factory()->for($this->user)->create([
            'title' => 'Overdue task',
            'is_completed' => false,
            'submission_date' => now()->subDays(2),
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Upcoming task',
            'is_completed' => false,
            'submission_date' => now()->addDays(2),
        ]);

        $response = $this->get(route('tasks.index', ['status' => 'overdue']));

        $response->assertSee('Overdue task');
        $response->assertDontSee('Upcoming task');
    }

    public function test_sort_orders_tasks_by_due_date_ascending(): void
    {
        $later = Task::factory()->for($this->user)->create([
            'title' => 'Later task',
            'submission_date' => now()->addDays(10),
        ]);
        $sooner = Task::factory()->for($this->user)->create([
            'title' => 'Sooner task',
            'submission_date' => now()->addDays(1),
        ]);

        $response = $this->get(route('tasks.index', ['sort' => 'due_asc']));

        $response->assertViewHas('tasks', function ($tasks) use ($sooner, $later) {
            return $sooner->id === $tasks->first()->id
                && $later->id === $tasks->last()->id;
        });
    }

    public function test_sort_orders_tasks_by_due_date_descending(): void
    {
        $later = Task::factory()->for($this->user)->create([
            'title' => 'Later task',
            'submission_date' => now()->addDays(10),
        ]);
        $sooner = Task::factory()->for($this->user)->create([
            'title' => 'Sooner task',
            'submission_date' => now()->addDays(1),
        ]);

        $response = $this->get(route('tasks.index', ['sort' => 'due_desc']));

        $response->assertViewHas('tasks', function ($tasks) use ($sooner, $later) {
            return $later->id === $tasks->first()->id
                && $sooner->id === $tasks->last()->id;
        });
    }

    public function test_status_and_sort_params_combine_correctly(): void
    {
        Task::factory()->for($this->user)->create([
            'title' => 'Completed early',
            'is_completed' => true,
            'submission_date' => now()->subDays(5),
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Completed late',
            'is_completed' => true,
            'submission_date' => now()->subDays(1),
        ]);
        Task::factory()->for($this->user)->create([
            'title' => 'Still active',
            'is_completed' => false,
        ]);

        $response = $this->get(route('tasks.index', [
            'status' => 'completed',
            'sort' => 'due_asc',
        ]));

        $response->assertSee('Completed early');
        $response->assertSee('Completed late');
        $response->assertDontSee('Still active');
    }

    public function test_pagination_preserves_query_string_params(): void
    {
        foreach (range(1, 11) as $taskNumber) {
            Task::factory()->for($this->user)->create([
                'title' => "Matching task {$taskNumber}",
                'is_completed' => false,
            ]);
        }

        $response = $this->get(route('tasks.index', [
            'status' => 'active',
            'sort' => 'oldest',
        ]));

        $response->assertOk();
        $response->assertSee('status=active', false);
        $response->assertSee('sort=oldest', false);
    }

    public function test_a_user_never_sees_another_users_tasks_via_search(): void
    {
        $otherUser = User::factory()->create();

        Task::factory()->for($this->user)->create([
            'title' => 'My own task',
            'description' => 'shared keyword here',
        ]);
        Task::factory()->for($otherUser)->create([
            'title' => "Someone else's task",
            'description' => 'shared keyword here',
        ]);

        $response = $this->get(route('tasks.index', ['q' => 'shared keyword']));

        $response->assertSee('My own task');
        $response->assertDontSee("Someone else's task");
    }

    public function test_a_user_never_sees_another_users_tasks_via_status_filter(): void
    {
        $otherUser = User::factory()->create();

        Task::factory()->for($this->user)->create([
            'title' => 'My completed task',
            'is_completed' => true,
        ]);
        Task::factory()->for($otherUser)->create([
            'title' => "Someone else's completed task",
            'is_completed' => true,
        ]);

        $response = $this->get(route('tasks.index', ['status' => 'completed']));

        $response->assertSee('My completed task');
        $response->assertDontSee("Someone else's completed task");
    }
}
