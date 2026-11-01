<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminCategory(): Category
{
    $category = new Category;
    $category->name = 'General';
    $category->save();

    return $category;
}

function adminTask(User $owner, Category $category): Task
{
    $task = new Task;
    $task->user_id = $owner->id;
    $task->category_id = $category->id;
    $task->title = 'A task title';
    $task->description = 'A task description.';
    $task->status = false;
    $task->is_urgent = false;
    $task->save();

    return $task;
}

test('regular users cannot access the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admins can access the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Admin dashboard');
});

test('regular users cannot edit another users task through a direct link', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $task = adminTask($owner, adminCategory());

    $this->actingAs($otherUser)
        ->get(route('tasks.edit', $task))
        ->assertForbidden();
});

test('admins can edit and delete another users task', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $owner = User::factory()->create();
    $task = adminTask($owner, adminCategory());

    $this->actingAs($admin)
        ->get(route('tasks.edit', $task))
        ->assertOk();

    $this->actingAs($admin)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index', absolute: false));

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('admins can change another users role but not their own role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $user), ['role' => 'admin'])
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);

    $this->actingAs($admin)
        ->patch(route('admin.users.role', $admin), ['role' => 'user'])
        ->assertUnprocessable();

    $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
});
