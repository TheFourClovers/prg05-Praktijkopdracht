<?php

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function taskAttributes(int $categoryId, bool $isUrgent = false): array
{
    return [
        'title' => 'A task title',
        'description' => 'A task description.',
        'category_id' => $categoryId,
        'is_urgent' => $isUrgent,
    ];
}

function createCategory(): Category
{
    $category = new Category;
    $category->name = 'General';
    $category->save();

    return $category;
}

test('users can mark a task urgent after placing five tasks', function () {
    $user = User::factory()->create();
    $category = createCategory();

    Task::unguarded(function () use ($user, $category): void {
        for ($index = 1; $index <= 5; $index++) {
            Task::query()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => "Existing task {$index}",
                'description' => 'An existing task.',
                'status' => false,
                'is_urgent' => false,
            ]);
        }
    });

    $response = $this->actingAs($user)->post(route('tasks.store'), taskAttributes($category->id, true));

    $response->assertRedirect(route('tasks.index', absolute: false));
    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'is_urgent' => true,
    ]);
});

test('users cannot mark a task urgent before placing five tasks', function () {
    $user = User::factory()->create();
    $category = createCategory();

    Task::unguarded(function () use ($user, $category): void {
        for ($index = 1; $index <= 4; $index++) {
            Task::query()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => "Existing task {$index}",
                'description' => 'An existing task.',
                'status' => false,
                'is_urgent' => false,
            ]);
        }
    });

    $response = $this->actingAs($user)->from(route('tasks.create'))
        ->post(route('tasks.store'), taskAttributes($category->id, true));

    $response->assertRedirect(route('tasks.create', absolute: false));
    $response->assertSessionHasErrors('is_urgent');
    $this->assertDatabaseMissing('tasks', [
        'user_id' => $user->id,
        'is_urgent' => true,
    ]);
});

test('urgent tasks are shown before regular tasks', function () {
    $user = User::factory()->create();
    $category = createCategory();

    Task::unguarded(function () use ($user, $category): void {
        Task::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Regular task',
            'description' => 'A regular task.',
            'status' => false,
            'is_urgent' => false,
        ]);

        Task::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Urgent task',
            'description' => 'An urgent task.',
            'status' => false,
            'is_urgent' => true,
        ]);
    });

    $response = $this->get(route('tasks.index'));

    $response->assertSeeInOrder(['Urgent task', 'Regular task']);
});

test('task owners can close their own open task', function () {
    $user = User::factory()->create();
    $category = createCategory();
    $task = new Task;
    $task->user_id = $user->id;
    $task->category_id = $category->id;
    $task->title = 'Task to close';
    $task->description = 'A task that is ready.';
    $task->status = false;
    $task->is_urgent = true;
    $task->save();

    $response = $this->actingAs($user)->post(route('tasks.close', $task));

    $response->assertRedirect(route('tasks.show', $task, absolute: false));
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => true,
    ]);
});

test('users cannot close another users task', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = createCategory();
    $task = new Task;
    $task->user_id = $owner->id;
    $task->category_id = $category->id;
    $task->title = 'Someone elses task';
    $task->description = 'This task belongs to another user.';
    $task->status = false;
    $task->is_urgent = false;
    $task->save();

    $response = $this->actingAs($otherUser)->post(route('tasks.close', $task));

    $response->assertForbidden();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => false,
    ]);
});

test('closed tasks are shown after all open tasks', function () {
    $user = User::factory()->create();
    $category = createCategory();

    Task::unguarded(function () use ($user, $category): void {
        Task::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Closed urgent task',
            'description' => 'A closed urgent task.',
            'status' => true,
            'is_urgent' => true,
        ]);

        Task::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Open regular task',
            'description' => 'An open regular task.',
            'status' => false,
            'is_urgent' => false,
        ]);
    });

    $response = $this->get(route('tasks.index'));

    $response->assertSeeInOrder(['Open regular task', 'Closed urgent task']);
});
