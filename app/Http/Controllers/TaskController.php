<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->paginate(10);
        $completed = auth()->user()->tasks()->where('is_completed', true)->count();

        return view('tasks.index', compact('tasks', 'completed'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function store(StoreTaskRequest $request)
    {
        auth()->user()->tasks()->create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task added!');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function toggle(Task $task)
    {
        $this->authorize('update', $task);

        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}