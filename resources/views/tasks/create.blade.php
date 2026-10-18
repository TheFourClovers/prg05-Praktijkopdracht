<x-app-layout :show-logo="false">
    <h1>Create new task</h1>

    <form action="{{ route('tasks.store') }}" method="post">
        @csrf

        <div>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"/>
            @error('title')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="{{ old('description') }}"/>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="category">Category</label>
            <input type="number" id="category" name="category" value="{{ old('category') }}"/>
            @error('category_id')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" name="submit">Save</button>
    </form>
</x-app-layout>
