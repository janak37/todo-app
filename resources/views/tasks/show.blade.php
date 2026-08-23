@extends('layouts.app')

@section('content')
    <p><a href="{{ route('tasks.index') }}">Back to tasks</a></p>
    <h1>{{ $task->title }}</h1>

    <p>
        <strong>Submission date:</strong>
        {{ $task->submission_date?->format('M j, Y') ?? 'Not set' }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ $task->is_completed ? 'Completed' : 'Pending' }}
    </p>

    <h2>Description</h2>
    <p>{{ $task->description ?: 'No description provided.' }}</p>

    <a href="{{ route('tasks.edit', $task) }}">Edit assignment</a>
@endsection