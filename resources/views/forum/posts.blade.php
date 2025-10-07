@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f172a] text-gray-100 py-12 px-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-white">{{ $category->name }}</h1>
            @auth
                <a href="{{ route('posts.create', $category->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition-all duration-300">
                    + New Post
                </a>
            @endauth
        </div>

        @if($posts->isEmpty())
            <p class="text-gray-400 text-lg">No discussions yet. Be the first to post!</p>
        @else
            <ul class="space-y-4">
                @foreach ($posts as $post)
    <li class="border border-blue-500/30 bg-blue-800/40 p-4 rounded-lg hover:bg-blue-700/50 hover:shadow-md transition-all duration-200">
        <a href="{{ route('posts.show', $post->id) }}" class="text-lg font-semibold text-blue-200 hover:underline">
            {{ $post->title }}
        </a>
        <p class="text-sm text-gray-300 mt-1">
            Posted by {{ $post->user->name ?? 'Unknown' }} · {{ $post->created_at->format('M d, Y') }}
        </p>
        <p class="text-sm text-gray-400 mt-1">
            💬 {{ $post->replies_count }} {{ Str::plural('reply', $post->replies_count) }}
        </p>
    </li>
@endforeach

            </ul>
        @endif
    </div>
</div>
@endsection
