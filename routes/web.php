<?php

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\QuizController as WebQuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
    Route::get('/user/{id}', [UserController::class, 'getUserById'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/quiz/{category}', [QuizController::class, 'showQuiz'])->name('quiz.show');
    Route::post('/quiz/store', [WebQuizController::class, 'store'])->name('quiz.store');
    
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/history', [ProfileController::class, 'showHistory'])->name('quiz.history');
        Route::get('/user/history/{user_id}', [WebQuizController::class, 'getQuizByUserId'])->name('user.history');
    });
});

Route::get('/api/questions', [QuizController::class, 'getQuestions'])->name('quiz.api');

// ✅ Fallback Route (Handles 404 errors)
Route::fallback([FallbackController::class, 'index'])->name('404');