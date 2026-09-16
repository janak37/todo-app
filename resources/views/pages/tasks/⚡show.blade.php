<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public Task $task;

    public function mount(Task $task): void
    {
        $this->authorize('view', $task);

        $this->task = $task;
    }

    public function toggle(): void
    {
        $this->authorize('update', $this->task);

        $this->task->update(['is_completed' => !$this->task->is_completed]);
    }

    public function delete()
    {
        $this->authorize('delete', $this->task);

        $this->task->delete();

        session()->flash('success', 'Task deleted!');

        return $this->redirectRoute('livewire.tasks.index', navigate: true);
    }
};
?>

<div class="mx-auto max-w-2xl">
    <a href="{{ route('livewire.tasks.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900"
        wire:navigate>← Back to tasks</a>
    <article class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <span
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $task->is_completed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $task->is_completed ? 'Completed' : 'In progress' }}</span>
                <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">{{ $task->title }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('livewire.tasks.edit', $task) }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold hover:bg-slate-50"
                    wire:navigate>Edit</a>
                <button wire:click="toggle" type="button"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold hover:bg-slate-50">
                    {{ $task->is_completed ? 'Mark pending' : 'Mark complete' }}
                </button>
                <button wire:click="delete" wire:confirm="Delete this task? This cannot be undone." type="button"
                    class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50">
                    Delete
                </button>
            </div>
        </div>
        <div class="mt-8 grid gap-5 border-y border-slate-100 py-6 sm:grid-cols-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Due date</p>
                <p class="mt-1 font-semibold">{{ $task->submission_date?->format('M j, Y') ?? 'No due date' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</p>
                <p class="mt-1 font-semibold">{{ $task->is_completed ? 'Complete' : 'Pending' }}</p>
            </div>
        </div>
        <div class="pt-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Description</h2>
            <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">
                {{ $task->description ?: 'No description provided.' }}</p>
        </div>
    </article>
</div>
