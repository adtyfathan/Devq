<?php

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ✅ Public Routes (Guests Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ✅ Protected Routes (Requires Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/quiz/{category}', [QuizController::class, 'showQuiz'])->name('quiz.show');
});

Route::get('api/questions', [QuizController::class, 'getQuestions'])->name('quiz.api');

// ✅ Fallback Route (Handles 404 errors)
Route::fallback([FallbackController::class, 'index'])->name('404');