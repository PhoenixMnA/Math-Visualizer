@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <div class="bg-[#1e293b] p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-white mb-6">Create a New Post</h1>

        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">

            {{-- Title --}}
            <div class="mb-6">
                <label for="title" class="block text-gray-300 font-semibold mb-2">Post Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full bg-[#0f172a] border border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter your post title" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Body --}}
            <div class="mb-6">
                <label for="body" class="block text-gray-300 font-semibold mb-2">Content</label>
                <textarea name="body" id="body" rows="6"
                          class="w-full bg-[#0f172a] border border-gray-600 rounded-lg p-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Write your thoughts here..." required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('posts.index', $category->id) }}"
                   class="text-gray-400 hover:text-white transition-colors">
                   ← Back to Discussions
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    Publish Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
