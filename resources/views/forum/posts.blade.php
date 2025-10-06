@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">{{ $category->name }} Discussions</h1>

    <a href="{{ route('forum.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Categories</a>

    <div class="mb-6">
        <a href="{{ route('posts.create', $category->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    + New Post
</a>

    </div>

    @if ($posts->isEmpty())
        <p class="text-gray-500">No posts yet in this category.</p>
    @else
        <ul class="space-y-4">
            @foreach ($posts as $post)
                <li class="border p-4 rounded hover:bg-gray-50">
                    <a href="{{ route('posts.show', $post->id) }}" class="text-lg font-semibold text-blue-700 hover:underline">
                        {{ $post->title }}
                    </a>
                    <p class="text-sm text-gray-500">Posted by {{ $post->user->name ?? 'Unknown' }} on {{ $post->created_at->format('M d, Y') }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
