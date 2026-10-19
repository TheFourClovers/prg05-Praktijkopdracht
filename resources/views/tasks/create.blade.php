<x-app-layout :show-logo="false">
    <main class="min-h-[calc(100vh-4rem)] bg-gray-100 px-4 py-12 dark:bg-gray-900 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">
                    Tasks
                </p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Create a new task
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Add the details below so people can find and complete your task.
                </p>
            </div>

            <form action="{{ route('tasks.store') }}" method="post"
                  class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 sm:p-8">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Title
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="5"
                              class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Category
                    </label>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Choose an existing category.
                    </p>
                    <select id="category_id" name="category_id"
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if ($canMarkUrgent)
                    <div>
                        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-200">
                            <input type="checkbox" name="is_urgent" value="1"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                   @checked(old('is_urgent'))>
                            Mark this task as urgent
                        </label>
                    </div>
                @endif

                <div class="flex justify-end border-t border-gray-200 pt-6 dark:border-gray-700">
                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Save task
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
