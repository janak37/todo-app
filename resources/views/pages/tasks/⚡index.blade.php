<?php

use App\Models\Task;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $status = 'all';

    #[Url(as: 'sort')]
    public string $sort = 'latest';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function getTasksProperty()
    {
        return auth()->user()->tasks()
            ->search($this->search)
            ->status($this->status)
            ->ordered($this->sort)
            ->paginate(10);
    }

    public function getCompletedProperty(): int
    {
        return auth()->user()->tasks()->where('is_completed', true)->count();
    }
};
?>

<div>
    <div class="mb-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
        <div>
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Your workspace</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">My tasks<span class="text-amber-500">.</span></h1>
            <p class="mt-3 text-slate-500">Keep the important things moving.</p>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex gap-6 border-l-2 border-amber-400 pl-4 text-sm">
                <div><p class="text-2xl font-bold">{{ $this->tasks->total() }}</p><p class="text-slate-500">Total</p></div>
                <div><p class="text-2xl font-bold">{{ $this->completed }}</p><p class="text-slate-500">Done</p></div>
            </div>
            <a href="{{ route('livewire.tasks.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                <span class="text-lg leading-none">+</span> New task
            </a>
        </div>
    </div>

    <div class="mb-8 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search title or description..."
            class="w-full flex-1 rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100"
        >

        <select wire:model.live="status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="overdue">Overdue</option>
        </select>

        <select wire:model.live="sort" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
            <option value="latest">Newest first</option>
            <option value="oldest">Oldest first</option>
            <option value="due_asc">Due date ↑</option>
            <option value="due_desc">Due date ↓</option>
        </select>

        @if($search || $status !== 'all' || $sort !== 'latest')
            <button wire:click="$set('search', ''); $set('status', 'all'); $set('sort', 'latest')" type="button" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Clear</button>
        @endif
    </div>

    @if($this->tasks->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center shadow-sm">
            @if($search || $status !== 'all')
                <p class="text-3xl">No matching tasks.</p>
                <p class="mt-2 text-slate-500">Try a different search or filter.</p>
            @else
                <p class="text-3xl">Nothing here yet.</p>
                <p class="mt-2 text-slate-500">Start with one small, clear task.</p>
            @endif
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_12px_40px_-20px_rgba(15,23,42,0.35)]">
            @foreach($this->tasks as $task)
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 last:border-0 sm:px-7" wire:key="task-{{ $task->id }}">
                    <div class="min-w-0">
                        <p class="truncate text-base font-bold {{ $task->is_completed ? 'text-slate-400 line-through' : 'text-slate-900' }}">{{ $task->title }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $task->description ? Str::limit($task->description, 90) : 'No description' }}</p>
                        @if($task->submission_date)<p class="mt-2 text-xs font-semibold text-slate-400">Due {{ $task->submission_date->format('M j, Y') }}</p>@endif
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <a href="{{ route('livewire.tasks.edit', $task) }}" class="font-semibold text-slate-500 hover:text-slate-900">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $this->tasks->links() }}
        </div>
    @endif
</div>