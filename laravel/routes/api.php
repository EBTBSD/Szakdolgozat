<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\course\CoursesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard-data', [\App\Http\Controllers\MainController::class, 'main']);
    Route::post('/courses/new', [CoursesController::class, 'newCourse']);
});

Route::post('/login', [AuthController::class, 'login_store']);
Route::post('/register', [AuthController::class, 'register_store']);
