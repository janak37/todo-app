@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">← Back to tasks</a>
        <article class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
            <div class="flex flex-wrap items-start justify-between gap-4"><div><span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $task->is_completed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $task->is_completed ? 'Completed' : 'In progress' }}</span><h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">{{ $task->title }}</h1></div><a href="{{ route('tasks.edit', $task) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold hover:bg-slate-50">Edit</a></div>
            <div class="mt-8 grid gap-5 border-y border-slate-100 py-6 sm:grid-cols-2"><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Due date</p><p class="mt-1 font-semibold">{{ $task->submission_date?->format('M j, Y') ?? 'No due date' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</p><p class="mt-1 font-semibold">{{ $task->is_completed ? 'Complete' : 'Pending' }}</p></div></div>
            <div class="pt-2"><h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Description</h2><p class="mt-3 whitespace-pre-line leading-7 text-slate-600">{{ $task->description ?: 'No description provided.' }}</p></div>
        </article>
    </div>
@endsection