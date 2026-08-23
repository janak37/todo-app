@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf @method('PUT')
        <input type="text" name="title" value="{{ $task->title }}">
        <textarea name="description">{{ $task->description }}</textarea>
        <label for="submission_date">Submission date</label>
        <input type="date" id="submission_date" name="submission_date" value="{{ old('submission_date', optional($task->submission_date)->format('Y-m-d')) }}">
        <button type="submit">Update</button>
    </form>
@endsection