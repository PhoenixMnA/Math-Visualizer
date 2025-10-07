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
                @foreach($posts as $post)
                    <li>
                        <a href="{{ route('posts.show', $post->id) }}"
                           class="block bg-[#1e293b] p-6 rounded-xl border border-gray-700 hover:border-blue-500 hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1">
                            <h2 class="text-2xl font-semibold text-white mb-1 group-hover:text-blue-400 transition-colors">{{ $post->title }}</h2>
                            <p class="text-gray-400 text-sm mb-2">{{ Str::limit($post->body, 120) }}</p>
                            <div class="text-xs text-gray-500">
                                Posted by <span class="text-blue-400">{{ $post->user->name ?? 'Unknown' }}</span> • {{ $post->created_at->diffForHumans() }}
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
