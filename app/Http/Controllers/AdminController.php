<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'users' => User::query()->latest()->get(),
            'tasks' => Task::query()->with(['user', 'category'])->latest()->get(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        abort_if($user->is(auth()->user()), 422, 'You cannot change your own role.');

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.dashboard');
    }
}
