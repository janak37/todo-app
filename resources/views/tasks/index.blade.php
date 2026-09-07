@extends('layouts.app')
@section('title', 'My Tasks · Taskflow')

@section('content')
    <div class="mb-10 flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
        <div>
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Your workspace</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">My tasks<span class="text-amber-500">.</span></h1>
            <p class="mt-3 text-slate-500">Keep the important things moving.</p>
        </div>
        <div class="flex gap-6 border-l-2 border-amber-400 pl-4 text-sm">
            <div><p class="text-2xl font-bold">{{ $tasks->total() }}</p><p class="text-slate-500">Total</p></div>
            <div><p class="text-2xl font-bold">{{ $completed }}</p><p class="text-slate-500">Done</p></div>
        </div>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="mb-8 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="Search title or description..."
            class="w-full flex-1 rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100"
        >

        <select name="status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
            <option value="all" @selected($status === 'all')>All</option>
            <option value="active" @selected($status === 'active')>Active</option>
            <option value="completed" @selected($status === 'completed')>Completed</option>
            <option value="overdue" @selected($status === 'overdue')>Overdue</option>
        </select>

        <select name="sort" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100">
            <option value="latest" @selected($sort === 'latest')>Newest first</option>
            <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
            <option value="due_asc" @selected($sort === 'due_asc')>Due date ↑</option>
            <option value="due_desc" @selected($sort === 'due_desc')>Due date ↓</option>
        </select>

        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>

        @if($search || $status !== 'all' || $sort !== 'latest')
            <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Clear</a>
        @endif
    </form>

    @if($tasks->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center shadow-sm">
            @if($search || $status !== 'all')
                <p class="text-3xl">No matching tasks.</p>
                <p class="mt-2 text-slate-500">Try a different search or filter.</p>
            @else
                <p class="text-3xl">Nothing here yet.</p>
                <p class="mt-2 text-slate-500">Start with one small, clear task.</p>
                <a href="{{ route('tasks.create') }}" class="mt-6 inline-block font-semibold text-amber-700 hover:text-amber-900">Create your first task →</a>
            @endif
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_12px_40px_-20px_rgba(15,23,42,0.35)]">
            @foreach($tasks as $task)
                <div class="group flex flex-col gap-4 border-b border-slate-100 px-5 py-5 last:border-0 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div class="flex min-w-0 items-start gap-4">
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="pt-1">
                            @csrf @method('PATCH')
                            <button aria-label="{{ $task->is_completed ? 'Mark task as pending' : 'Mark task as complete' }}" class="grid size-6 place-items-center rounded-full border-2 {{ $task->is_completed ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 hover:border-amber-500' }} transition">{{ $task->is_completed ? '✓' : '' }}</button>
                        </form>
                        <div class="min-w-0">
                            <a class="block truncate text-base font-bold {{ $task->is_completed ? 'text-slate-400 line-through' : 'text-slate-900 hover:text-amber-700' }}" href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                            <p class="mt-1 text-sm text-slate-500">{{ $task->description ? Str::limit($task->description, 90) : 'No description' }}</p>
                            @if($task->submission_date)<p class="mt-2 text-xs font-semibold text-slate-400">Due {{ $task->submission_date->format('M j, Y') }}</p>@endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4 pl-10 text-sm sm:pl-0">
                        <a class="font-semibold text-slate-500 hover:text-slate-900" href="{{ route('tasks.edit', $task) }}">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="font-semibold text-rose-500 hover:text-rose-700" onclick="return confirm('Delete this task?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection