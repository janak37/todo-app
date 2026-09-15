<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireTasksIndexTest extends TestCase
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

        Livewire::test('pages::tasks.index')
            ->set('search', 'kitchen')
            ->assertSee('Clean the house')
            ->assertDontSee('Buy groceries');
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

        Livewire::test('pages::tasks.index')
            ->set('status', 'completed')
            ->assertSee('Completed task')
            ->assertDontSee('Active task');
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

        $component = Livewire::test('pages::tasks.index')
            ->set('sort', 'due_asc');

        $tasks = $component->get('tasks');

        $this->assertEquals($sooner->id, $tasks->first()->id);
        $this->assertEquals($later->id, $tasks->last()->id);
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

        Livewire::test('pages::tasks.index')
            ->set('search', 'shared keyword')
            ->assertSee('My own task')
            ->assertDontSee("Someone else's task");
    }
}
