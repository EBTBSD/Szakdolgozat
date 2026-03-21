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
    
    Route::get('/courses/{id}', [CoursesController::class, 'getCourseDetails']);
    Route::post('/courses/{id}/assignments/new', [CoursesController::class, 'newAssignment']);
	Route::get('/assignments/{id}', [CoursesController::class, 'getAssignmentDetails']);
    Route::post('/student/assignments/{id}/submit', [CoursesController::class, 'submitTest']);
    Route::get('/student/assignments/{id}/test', [CoursesController::class, 'getTestForStudent']);
    Route::post('/join-course', [CoursesController::class, 'joinCourseWithCode']);

});
Route::delete('/assignments/{id}', [CoursesController::class, 'deleteAssignment']);
Route::post('/login', [AuthController::class, 'login_store']);
Route::post('/register', [AuthController::class, 'register_store']);
Route::put('/assignments/{id}', [CoursesController::class, 'updateAssignment']);
Route::get('/assignments/{id}/questions', [CoursesController::class, 'getQuestions']);
Route::post('/assignments/{id}/questions', [CoursesController::class, 'addQuestion']);
Route::post('/questions/{id}/answers', [CoursesController::class, 'addAnswer']);
Route::delete('/questions/{id}', [CoursesController::class, 'deleteQuestion']);
Route::delete('/answers/{id}', [CoursesController::class, 'deleteAnswer']);
Route::get('/assignments/{id}/submissions', [CoursesController::class, 'getSubmissions']);
