<?php



use App\Http\Controllers\MathController;

Route::get('/math', [MathController::class, 'index']);
Route::post('/math/save', [MathController::class, 'save']);
Route::post('/math/clear', [MathController::class, 'clear']);
