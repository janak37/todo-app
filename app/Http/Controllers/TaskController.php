<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        $completed = Task::completedCount();

        return view('tasks.index', compact('tasks', 'completed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTask($request);

        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task added!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $this->validateTask($request);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    private function validateTask(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'submission_date' => 'nullable|date',
        ]);
    }

    /**
     * Toggle the completion status of the task.
     */
    public function toggle(Task $task)
    {
       $task->update(['is_completed' => !$task->is_completed]);
       return back();

      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}
    