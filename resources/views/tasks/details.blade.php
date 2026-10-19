<x-layout title="{{ $task->title }}">
    <div class="min-h-screen px-6 py-12">
        <div class="mx-auto max-w-3xl">
            <a href="{{ route('tasks.index') }}"
               class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">
                &larr; Back to tasks
            </a>

            <article class="mt-6 rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-xl sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-400">
                            Task details
                        </p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">
                            {{ $task->title }}
                        </h1>
                    </div>

                    <span class="{{ $task->status ? 'text-emerald-400' : 'text-slate-400' }} rounded-full bg-slate-800 px-3 py-1 text-sm">
                        {{ $task->status ? 'Closed' : 'Open' }}
                    </span>
                </div>

                <div class="mt-8 border-t border-slate-800 pt-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">
                        Description
                    </h2>
                    <div class="mt-3 whitespace-pre-line text-base leading-7 text-white">
                        {{ $task->description ?: 'No description available.' }}
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between border-t border-slate-800 pt-6 text-sm">
                    <span class="rounded-full bg-slate-800 px-3 py-1 text-slate-300">
                        {{ $task->category?->name ?? 'Unknown category' }}
                    </span>

                    @if ($task->is_urgent)
                        <span class="rounded-full bg-red-100 px-3 py-1 font-medium text-red-700">
                            Urgent
                        </span>
                    @endif
                </div>

                @auth
                    @if (auth()->id() === $task->user_id)
                        <div class="mt-6 flex gap-3 border-t border-slate-800 pt-6">
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">
                                Edit task
                            </a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">
                                    Delete task
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </article>
        </div>
    </div>
</x-layout>
