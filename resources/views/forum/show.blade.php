@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
    <p class="text-gray-500 mb-4">Posted by {{ $post->user->name ?? 'Unknown' }} on {{ $post->created_at->format('M d, Y') }}</p>
    <div class="bg-white p-4 rounded shadow mb-6 prose">
        <p>{{ $post->body }}</p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Replies</h2>

    @if ($post->replies->isEmpty())
        {{-- This is the correct content for the @if block --}}
        <p class="text-gray-600">Be the first to reply!</p>
    @else
        <ul class="space-y-4">
            @foreach ($post->replies as $reply)
                <li class="border p-3 rounded bg-gray-50">
                    <p>{{ $reply->body }}</p>
                    <small class="text-gray-500">by {{ $reply->user->name ?? 'Unknown' }} &middot; {{ $reply->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="mt-6">
        @auth
            <form action="{{ route('replies.store', $post) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="mb-4">
                    <label for="body" class="block text-gray-700 font-bold mb-2">Your Reply:</label>
                    <textarea name="body" id="body" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('body') border-red-500 @enderror" required></textarea>
                    @error('body')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Post Reply
                </button>
            </form>
        @else
            <p><a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log in</a> to post a reply.</p>
        @endauth
    </div>
</div>
@endsection