<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\contact\ContactController;
use App\Http\Controllers\course\CoursesController;

Route::get('/', [MainController::class, 'main'])->name('main.main');

Route::get('/courses/{id}', [CoursesController::class, 'courses'])->name('courses.courses');
Route::post('/newCourse', [CoursesController::class, 'newCourse'])->name('coruses.newCoruses');

Route::get('/authentication', [AuthController::class, 'show_form'])->name('auth.show_form');
Route::post('/register', [AuthController::class, 'register_store'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login_store'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/contact', [ContactController::class, 'contact'])->name('contact.contact');
