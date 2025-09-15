<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equation;   // ✅ Import the Equation model

class MathController extends Controller
{
    // Show the main math visualizer page
    public function index()
    {
        // Get the 10 most recent equations
        $equations = Equation::latest()->take(10)->get();

        return view('math.index', compact('equations'));
    }

    // Save a new equation (AJAX request from JS)
    public function save(Request $request)
    {
        $request->validate([
            'expression' => 'required|string|max:255',
        ]);

        $equation = Equation::create([
            'expression' => $request->input('expression'),
        ]);

        return response()->json([
            'status' => 'success',
            'equation' => $equation,
        ]);
    }
    public function clear()
{
    \App\Models\Equation::truncate(); // clears all history
    return redirect('/math');
}

}
