<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HighlightController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Highlight routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tests/{test}/highlights', [HighlightController::class, 'index']);
    Route::post('/highlights', [HighlightController::class, 'store']);
    Route::delete('/highlights/{highlight}', [HighlightController::class, 'destroy']);
    Route::delete('/tests/{test}/highlights', [HighlightController::class, 'destroyAll']);
});
