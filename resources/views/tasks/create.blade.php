@extends('layouts.app')

@section('content')
    <h1>Add Task</h1>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Task title" value="{{ old('title') }}">
        <textarea name="description" placeholder="Description"></textarea>
        <label for="submission_date">Submission date</label>
        <input type="date" id="submission_date" name="submission_date" value="{{ old('submission_date') }}">
        <button type="submit">Save</button>
    </form>
    @error('title') <p style="color:red">{{ $message }}</p> @enderror
@endsection