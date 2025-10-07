@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-[#1e293b] p-6 rounded-xl shadow mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $post->title }}</h1>
        <p class="text-gray-400 mb-4">Posted by {{ $post->user->name ?? 'Unknown' }} on {{ $post->created_at->format('M d, Y') }}</p>
        <div class="prose prose-invert">
            <p class="text-gray-200">{{ $post->body }}</p>
        </div>

        @if(auth()->check() && auth()->id() === $post->user_id)
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors">
                    Delete Post
                </button>
            </form>
        @endif
    </div>

    <h2 class="text-2xl font-semibold mb-4 text-white">Replies</h2>

    @if ($post->replies->isEmpty())
        <p class="text-gray-400">Be the first to reply!</p>
    @else
        <ul class="space-y-4">
            @foreach ($post->replies as $reply)
                <li class="bg-[#1e293b] p-4 rounded-lg shadow">
                    <p class="text-gray-100">{{ $reply->body }}</p>
                    <small class="text-gray-400 block mt-1">by {{ $reply->user->name ?? 'Unknown' }}</small>

                    @if(auth()->check() && auth()->id() === $reply->user_id)
                        <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white px-2 py-1 rounded text-sm hover:bg-red-700 transition-colors">
                                Delete Reply
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <div class="mt-6">
        @auth
            <form action="{{ route('replies.store', $post) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="body" id="body" rows="4"
                          class="w-full p-3 bg-[#0f172a] border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Write your reply..." required></textarea>
                <button type="submit"
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Post Reply
                </button>
            </form>
        @else
            <p class="text-gray-400"><a href="{{ route('login') }}" class="text-blue-400 hover:underline">Log in</a> to post a reply.</p>
        @endauth
    </div>
</div>
@endsection
