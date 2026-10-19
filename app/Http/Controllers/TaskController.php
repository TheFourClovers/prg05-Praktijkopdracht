<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $categoryId = $request->integer('category_id');

        $tasks = Task::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, function ($query) use ($categoryId): void {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->get();

        return view('tasks', [
            'tasks' => $tasks,
            'search' => $search,
            'categories' => Category::query()->orderBy('name')->get(),
            'categoryId' => $categoryId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view(view: 'tasks.create', data: [
            'categories' => $categories,
            'canMarkUrgent' => $this->canMarkUrgent(),
        ]);
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
            'is_urgent' => 'sometimes|boolean',
        ]);

        $isUrgent = $request->boolean('is_urgent');

        if ($isUrgent && ! $this->canMarkUrgent()) {
            throw ValidationException::withMessages([
                'is_urgent' => 'You can mark a task as urgent after closing five tasks.',
            ]);
        }
        // error handling V
        // info de database in
        $task = new Task;
        $task->title = $request->input(key: 'title');
        $task->user_id = auth()->id();
        $task->category_id = $request->input(key: 'category_id');
        $task->status = false;
        $task->description = $request->input(key: 'description');
        $task->is_urgent = $isUrgent;

        $task->save();
        // redirect naar tasks.index

        return redirect()->route(route: 'tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('category');

        return view(view: 'tasks.details', data: [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        abort_unless(auth()->id() === $task->user_id, 403);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view(view: 'tasks.edit', data: [
            'task' => $task,
            'categories' => $categories,
            'canMarkUrgent' => $this->canMarkUrgent(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        abort_unless(auth()->id() === $task->user_id, 403);

        $validated = $request->validate([
            'title' => 'required|min:3|max:100',
            'description' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|boolean',
            'is_urgent' => 'sometimes|boolean',
        ]);

        $isUrgent = $this->canMarkUrgent()
            ? $request->boolean('is_urgent')
            : $task->is_urgent;

        if ($isUrgent && ! $this->canMarkUrgent()) {
            throw ValidationException::withMessages([
                'is_urgent' => 'You can mark a task as urgent after closing five tasks.',
            ]);
        }

        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->category_id = $validated['category_id'];
        $task->status = $validated['status'];
        $task->is_urgent = $isUrgent;
        $task->save();

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        abort_unless(auth()->id() === $task->user_id, 403);

        $task->delete();

        return redirect()->route(route: 'tasks.index');
    }

    private function canMarkUrgent(): bool
    {
        return auth()->user()->tasks()
            ->where('status', true)
            ->count() >= 5;
    }
}
