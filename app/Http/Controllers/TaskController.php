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
        $tasks = Task::query()->latest()->get();

        return view('tasks', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // een leeg form laten zien.
        return view(view: 'tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // info uit form halen V
        // check voor sql-injections V
        // validatie V
        $request->validate([
            'title' => 'required|min:3|max:100',
            'description' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        // error handling V
        // info de database in
        $task = new Task;
        $task->title = $request->input(key: 'title');
        $task->user_id = auth()->id();
        $task->category_id = $request->input(key: 'category_id');
        $task->status = false;
        $task->description = $request->input(key: 'description');
        $task->is_urgent = false;

        $task->save();
        // redirect naar tasks.index

        return redirect()->route(route: 'tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
