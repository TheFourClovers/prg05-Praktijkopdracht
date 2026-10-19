<x-layout title="Tasks">
    <div class="min-h-screen bg-slate-400 px-6 py-12">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-black">Community taskboard</p>
                    <h1 class="mt-2 text-4xl font-bold tracking-tight text-slate-900">Find a task</h1>
                    <p class="mt-3 text-slate-600">Find a task that matches your skills.</p>
                </div>

                @auth
                    <a href="{{ route('tasks.create') }}"
                       class="rounded-lg bg-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-950/40 transition hover:bg-indigo-400">
                        Post a task
                    </a>
                @endauth
            </div>

            <form action="{{ route('tasks.index') }}" method="get" class="mb-8 flex max-w-4xl flex-wrap gap-3">
                <label for="search" class="sr-only">Search tasks</label>
                <input type="search" id="search" name="search" value="{{ $search }}"
                       placeholder="Search by title or description..."
                       class="min-w-64 flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <label for="category_id" class="sr-only">Filter by category</label>
                <select id="category_id" name="category_id"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Search
                </button>
            </form>

            @if ($tasks->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900 p-10 text-center text-slate-400">
                    {{ $search !== '' ? 'No tasks matched your search.' : 'No tasks have been posted yet.' }}
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($tasks as $task)
                        <a href="{{ route('tasks.show', $task) }}"
                           class="group block rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-100">
                            <article class="flex h-full flex-col rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-xl shadow-black/10 transition group-hover:-translate-y-1 group-hover:border-indigo-500/60 group-hover:shadow-indigo-950/30">
                                <div class="flex items-start justify-between gap-4">
                                    <h2 class="text-xl font-semibold text-white">{{ $task->title }}</h2>

                                    @if ($task->is_urgent)
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                            Urgent
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-4 flex-1 text-sm leading-6 text-slate-400">
                                    {{ \Illuminate\Support\Str::limit($task->description, 30, '...') }}
                                </p>

                                <div class="mt-6 flex items-center justify-between border-t border-slate-800 pt-4 text-sm">
                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-slate-300">
                                        {{ $task->category?->name ?? 'Unknown category' }}
                                    </span>
                                    <span class="{{ $task->status ? 'text-emerald-400' : 'text-slate-500' }}">
                                        {{ $task->status ? 'Closed' : 'Open' }}
                                    </span>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
