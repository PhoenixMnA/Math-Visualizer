<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * Store a newly created reply in storage.
     */
    public function store(Request $request, Post $post)
    {
        // 1. Validate the incoming request data
        $validated = $request->validate([
            'body' => 'required|string|min:5',
        ]);

        // 2. Create the reply
        $post->replies()->create([
            'body' => $validated['body'],
            'user_id' => auth()->id(), // Associate the reply with the logged-in user
        ]);

        // 3. Redirect back to the post page with a success message
        return back()->with('success', 'Your reply has been posted!');
    }
}