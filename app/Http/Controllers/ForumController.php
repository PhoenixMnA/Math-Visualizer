<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    // Show all forum categories
    public function index()
    {
        $categories = Category::all();
        return view('forum.index', compact('categories'));
    }
}
