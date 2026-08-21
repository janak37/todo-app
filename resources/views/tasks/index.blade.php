@extends('layouts.app')

@section('content')
    <h1>My Tasks</h1>
    <a href="{{ route('tasks.create') }}">+ Add Task</a>

    @foreach($tasks as $task)
        <div class="task">
            <span class="{{ $task->is_completed ? 'completed' : '' }}">
                {{ $task->title }}
            </span>
            <span>
                <form action="{{ route('tasks.toggle', $task) }}" method="POST" style="display:inline">
                    @csrf @method('PATCH')
                    <button>{{ $task->is_completed ? 'Undo' : 'Done' }}</button>
                </form>
                <a href="{{ route('tasks.edit', $task) }}">Edit</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this task?')">Delete</button>
                </form>
            </span>
        </div>
    @endforeach
@endsection