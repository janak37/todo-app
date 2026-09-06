<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTaskRequest;
use App\Http\Requests\EditTaskRequest;
use App\Http\Requests\ShowTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\ToggleTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        ds(auth()->user())->label('index: authenticated user');

        $tasksQuery = auth()->user()->tasks()->latest();
        ds($tasksQuery->toSql())->label('index: tasks query SQL');

        $tasks = $tasksQuery->paginate(10);
        ds($tasks->total())->label('index: total tasks count');

        $completed = auth()->user()->tasks()->where('is_completed', true)->count();

        return view('tasks.index', compact('tasks', 'completed'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function show(ShowTaskRequest $request, Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function store(StoreTaskRequest $request)
    {
        ds($request->validated())->label('store: validated request data');

        $task = auth()->user()->tasks()->create($request->validated());

        ds($task)->label('store: task saved (note user_id)');

        return to_route('tasks.index')->with('success', 'Task added!');
    }

    public function edit(EditTaskRequest $request, Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return to_route('tasks.index')->with('success', 'Task updated!');
    }

    public function toggle(ToggleTaskRequest $request, Task $task)
    {
        $task->update(['is_completed' => ! $task->is_completed]);

        return back();
    }

    public function destroy(DestroyTaskRequest $request, Task $task)
    {
        $task->delete();

        return to_route('tasks.index')->with('success', 'Task deleted!');
    }
}