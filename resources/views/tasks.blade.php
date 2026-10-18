<x-layout title="Tasks">
    <div class="mx-auto max-w-7xl px-6 py-10">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tasks</h1>
                <p class="mt-2 text-gray-600">Find a task that matches your skills.</p>
            </div>

            @auth
                <a href="{{ route('tasks.create') }}"
                   class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Post a task
                </a>
            @endauth
        </div>

        @if ($tasks->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-300 bg-white p-10 text-center text-gray-600">
                No tasks have been posted yet.
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($tasks as $task)
                    <article class="flex flex-col rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $task->title }}</h2>

                            @if ($task->is_urgent)
                                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                    Urgent
                                </span>
                            @endif
                        </div>

                        <p class="mt-4 flex-1 text-sm leading-6 text-gray-600">
                            {{ $task->description }}
                        </p>

                        <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4 text-sm">
                            <span class="text-gray-500">Category {{ $task->category_id }}</span>
                            <span class="{{ $task->status ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $task->status ? 'Completed' : 'Open' }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
