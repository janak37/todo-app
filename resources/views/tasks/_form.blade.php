@php
    $task = $task ?? null;
@endphp

<div>
    <label for="title" class="mb-2 block text-sm font-bold">Task title</label>
    <input id="title" type="text" name="title" placeholder="What needs doing?" value="{{ old('title', $task?->title ?? '') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
    @error('title')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="description" class="mb-2 block text-sm font-bold">Description</label>
    <textarea id="description" name="description" rows="5" required placeholder="Add a little context..." class="w-full resize-y rounded-lg border border-slate-300 px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">{{ old('description', $task?->description ?? '') }}</textarea>
    @error('description')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>
<div>
    <label for="submission_date" class="mb-2 block text-sm font-bold">Due date</label>
    <input type="date" id="submission_date" name="submission_date" required value="{{ old('submission_date', $task?->submission_date?->format('Y-m-d') ?? '') }}" class="rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10">
    @error('submission_date')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>