<?php

use App\Http\Requests\Concerns\HasTaskRules;
use Livewire\Component;

new class extends Component {
    use HasTaskRules;

    public string $title = '';

    public string $description = '';

    public string $submission_date = '';

    public function rules(): array
    {
        return $this->taskRules();
    }

    public function save()
    {
        $validated = $this->validate();

        auth()->user()->tasks()->create($validated);

        session()->flash('success', 'Task added!');

        return $this->redirectRoute('livewire.tasks.index', navigate: true);
    }
};
?>

<div>
    <h1 class="mb-6 text-3xl font-black tracking-tight">New task<span class="text-amber-500">.</span></h1>

    <form wire:submit="save" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <label for="title" class="mb-2 block text-sm font-bold">Task title</label>
            <input id="title" type="text" wire:model="title" placeholder="What needs doing?"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('title')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="description" class="mb-2 block text-sm font-bold">Description</label>
            <textarea id="description" wire:model="description" rows="5" placeholder="Add a little context..."
                class="w-full resize-y rounded-lg border border-slate-300 px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10"></textarea>
            @error('description')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="submission_date" class="mb-2 block text-sm font-bold">Due date</label>
            <input type="date" id="submission_date" wire:model="submission_date"
                class="rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
            @error('submission_date')
                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-6">
            <a href="{{ route('livewire.tasks.index') }}"
                class="text-sm font-semibold text-slate-500 hover:text-slate-900" wire:navigate>Cancel</a>
            <button type="submit"
                class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Create
                task</button>
        </div>
    </form>
</div>
