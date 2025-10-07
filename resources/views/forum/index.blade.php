@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f172a] text-gray-100 py-12 px-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-4xl font-bold mb-10 text-white text-center">Community Forum</h1>

        @if($categories->isEmpty())
            <p class="text-center text-gray-400 text-lg">No categories yet — check back soon!</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('posts.index', $category->id) }}"
                       class="group block bg-[#1e293b] rounded-2xl p-6 shadow-lg border border-gray-700 hover:border-blue-500 hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        <h2 class="text-2xl font-semibold mb-2 text-white group-hover:text-blue-400 transition-colors">{{ $category->name }}</h2>
                        <p class="text-gray-400 mb-4">{{ Str::limit($category->description, 80) }}</p>
                        <div class="text-sm text-gray-500">
                            {{ $category->posts_count ?? 0 }} posts
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
