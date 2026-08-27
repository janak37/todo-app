@extends('layouts.app')
@section('title', 'New Task · Taskflow')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">← Back to tasks</a>
        <div class="mb-8 mt-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-600">Make a plan</p><h1 class="mt-2 text-4xl font-black tracking-tight">New task<span class="text-amber-500">.</span></h1></div>
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @include('tasks._form')
            <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6"><a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Cancel</a><button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Save task</button></div>
        </form>
    </div>
@endsection