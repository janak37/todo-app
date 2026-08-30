<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        $completed = Task::completedCount();

        return view('tasks.index', compact('tasks', 'completed'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task added!');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function toggle(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}