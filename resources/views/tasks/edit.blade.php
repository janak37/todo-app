@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">← Back to task</a>
        <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Refine the details</p><h1 class="mt-2 text-4xl font-black tracking-tight">Edit task<span class="text-amber-500">.</span></h1></div>
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf @method('PUT')
            <div><label for="title" class="mb-2 block text-sm font-bold">Task title</label><input id="title" type="text" name="title" value="{{ old('title', $task->title) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">@error('title')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
            <div><label for="description" class="mb-2 block text-sm font-bold">Description</label><textarea id="description" name="description" rows="5" class="w-full resize-y rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">{{ old('description', $task->description) }}</textarea></div>
            <div><label for="submission_date" class="mb-2 block text-sm font-bold">Due date</label><input type="date" id="submission_date" name="submission_date" value="{{ old('submission_date', optional($task->submission_date)->format('Y-m-d')) }}" class="rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10"></div>
            <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6"><a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Cancel</a><button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Update task</button></div>
        </form>
    </div>
@endsection