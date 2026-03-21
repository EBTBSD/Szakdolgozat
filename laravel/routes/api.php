<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\course\CoursesController;
use App\Http\Controllers\profile\ProfileController;

Route::post('/login', [AuthController::class, 'login_store']);
Route::post('/register', [AuthController::class, 'register_store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    
    // Felhasználó és Profil
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);
    
    // Dashboard
    Route::get('/dashboard-data', [\App\Http\Controllers\MainController::class, 'main']);
    
    // Kurzusok
    Route::post('/courses/new', [CoursesController::class, 'newCourse']);
    Route::get('/courses/{id}', [CoursesController::class, 'getCourseDetails']);
    Route::post('/join-course', [CoursesController::class, 'joinCourseWithCode']);
    
    // Feladatok (Assignments)
    Route::post('/courses/{id}/assignments/new', [CoursesController::class, 'newAssignment']);
    Route::get('/assignments/{id}', [CoursesController::class, 'getAssignmentDetails']);
    Route::put('/assignments/{id}', [CoursesController::class, 'updateAssignment']);
    Route::delete('/assignments/{id}', [CoursesController::class, 'deleteAssignment']);
    Route::get('/assignments/{id}/submissions', [CoursesController::class, 'getSubmissions']);
    
    // Diák funkciók (Teszt kitöltés)
    Route::get('/student/assignments/{id}/test', [CoursesController::class, 'getTestForStudent']);
    Route::post('/student/assignments/{id}/submit', [CoursesController::class, 'submitTest']);
    
    // Kérdések és Válaszok (Tanári funkciók)
    Route::get('/assignments/{id}/questions', [CoursesController::class, 'getQuestions']);
    Route::post('/assignments/{id}/questions', [CoursesController::class, 'addQuestion']);
    Route::delete('/questions/{id}', [CoursesController::class, 'deleteQuestion']);
    Route::post('/questions/{id}/answers', [CoursesController::class, 'addAnswer']);
    Route::delete('/answers/{id}', [CoursesController::class, 'deleteAnswer']);
    
});
