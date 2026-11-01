<x-layout title="Admin dashboard">
    <div class="min-h-screen bg-slate-400 px-6 py-12">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-wider text-indigo-900">Admin area</p>
                <h1 class="mt-2 text-4xl font-bold tracking-tight text-slate-900">Admin dashboard</h1>
                <p class="mt-3 text-slate-700">Manage users and moderate tasks.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl bg-white p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-slate-900">Users</h2>
                    <div class="mt-4 divide-y divide-slate-200">
                        @foreach ($users as $user)
                            <div class="flex items-center justify-between gap-4 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                </div>
                                @if ($user->is(auth()->user()))
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        Admin (you)
                                    </span>
                                @else
                                    <form action="{{ route('admin.users.role', $user) }}" method="post" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="role-{{ $user->id }}" class="sr-only">Role for {{ $user->name }}</label>
                                        <select id="role-{{ $user->id }}" name="role"
                                                class="rounded-md border-slate-300 text-sm"
                                                onchange="this.form.submit()">
                                            <option value="user" @selected($user->role === 'user')>User</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        </select>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-2xl bg-white p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-slate-900">All tasks</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($tasks as $task)
                            <article class="rounded-lg border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-indigo-700 hover:text-indigo-500">
                                            {{ $task->title }}
                                        </a>
                                        <p class="text-sm text-slate-500">
                                            {{ $task->user?->name ?? 'Unknown user' }} · {{ $task->status ? 'Closed' : 'Open' }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('tasks.edit', $task) }}" class="rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500">
                                            Edit
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-500">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="text-slate-500">No tasks found.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layout>
