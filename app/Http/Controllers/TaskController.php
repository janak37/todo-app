<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Task::create($request->only('title', 'description'));
        return redirect()->route('tasks.index')->with('success', 'Task added!');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($request->only('title', 'description'));
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function toggle(Task $task)
    {
       $task->update(['is_completed' => !$task->is_completed]);
       return back();

      
    }
}
    