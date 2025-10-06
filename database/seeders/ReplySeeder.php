<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\Post;
use App\Models\User;

class ReplySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // use our test user
        $posts = Post::all();

        foreach ($posts as $post) {
            Reply::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'body'    => "This is a sample reply to the post: {$post->title}",
            ]);
        }
    }
}
