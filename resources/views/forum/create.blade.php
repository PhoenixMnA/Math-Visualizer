@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">New Post in {{ $category->name }}</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category->id }}">

        <div class="mb-4">
            <label for="title" class="block font-semibold">Title</label>
            <input type="text" name="title" id="title" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label for="body" class="block font-semibold">Body</label>
            <textarea name="body" id="body" rows="5" class="w-full border rounded p-2" required></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Post
        </button>
    </form>

    <a href="{{ route('posts.index', $category->id) }}" class="block mt-4 text-blue-500 hover:underline">
        ← Back to {{ $category->name }} Discussions
    </a>
</div>
@endsection
