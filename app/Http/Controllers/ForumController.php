<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    // Show all forum categories
    public function index()
{
    // Fetch all categories with a count of their posts
    $categories = \App\Models\Category::withCount('posts')->get();

    return view('forum.index', compact('categories'));
}

    }

