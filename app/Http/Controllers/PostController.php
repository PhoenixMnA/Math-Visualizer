<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
   public function index($id)
{
    // Find the category
    $category = \App\Models\Category::findOrFail($id);

    // Fetch posts with user and replies count
    $posts = \App\Models\Post::where('category_id', $id)
        ->with(['user'])
        ->withCount('replies') // 👈 Add this line
        ->latest()
        ->get();

    return view('forum.posts', compact('category', 'posts'));
}


    public function show($id)
    {
        $post = Post::with(['user', 'replies.user'])->findOrFail($id);

        return view('forum.show', compact('post'));
    }

    public function create($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('forum.create', compact('category'));
    }

    // 🧩 Paste this new method BELOW create()
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        



        // Create the new post
        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
        ]);

        // Redirect back to the category discussion page
        return redirect()->route('posts.index', $post->category_id)
                         ->with('success', 'Your post has been created!');
    }
    public function destroy($id)
{
    $post = Post::findOrFail($id);

    // Only the author can delete their own post
    if ($post->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $post->delete();

    return redirect()->route('forum.index')->with('success', 'Post deleted successfully.');
}


}
