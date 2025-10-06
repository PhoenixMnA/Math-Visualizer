@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Forum Categories</h1>

    <ul>
        @foreach($categories as $category)
            <li>
                <a href="{{ route('posts.index', $category->id) }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
