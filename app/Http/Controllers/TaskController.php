<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement task listing with pagination (PR #2)
        return view('tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Implement task creation form (PR #3)
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement task storage logic (PR #3)
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // TODO: Implement task detail view if needed
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // TODO: Implement task edit form (PR #4)
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // TODO: Implement task update logic (PR #4)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // TODO: Implement task deletion logic (PR #5)
    }
}
