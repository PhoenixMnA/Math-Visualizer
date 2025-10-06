<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user (you can adjust later for multiple)
        $user = User::first();

        // Loop through categories and make example posts
        $categories = Category::all();

        foreach ($categories as $category) {
            Post::create([
                'user_id'     => $user->id,
                'category_id' => $category->id,
                'title'       => "Intro to {$category->name}",
                'body'        => "This is a starter discussion about {$category->name}.",
            ]);
        }
    }
}
