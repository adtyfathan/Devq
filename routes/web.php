<?php

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\QuizController as WebQuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MultiplayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TemplateController;
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
    
    Route::get('/summary', [SummaryController::class, 'index'])->name("summary.show");
    
    Route::get('/create/quiz', [WebQuizController::class, 'createQuizView'])->name('quiz.create');

    Route::get('/quiz/{category}', [QuizController::class, 'showQuiz'])->name('quiz.show');
    
    
    Route::get('/multiplayer/host/{category}', [MultiplayerController::class, 'showHostView'])->name('quiz.host');

    Route::get('/multiplayer/player/{lobby_id}', [MultiplayerController::class, 'showPlayerView'])->name('quiz.player');
    
    
    Route::get('/review', [ReviewController::class, 'index'])->name('review.show');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/history', [ProfileController::class, 'showHistory'])->name('quiz.history');
    });
});

Route::prefix('api')->group(function(){
    Route::get('/quiz/{id}', [WebQuizController::class, "getQuizById"]);
    Route::get('/question/{id}', [QuestionController::class, 'getQuestionById']);
    Route::get('/questions', [QuizController::class, 'getQuestions']);
    
    Route::get('/multiplayer/host/{category}', [MultiplayerController::class, 'createLobby']);
    Route::post('/template/store', [TemplateController::class, 'storeTemplate']);
    
    Route::get('/multiplayer/lobby/{lobby_id}', [MultiplayerController::class, 'getLobbyDetail']);
   
    Route::post('/quiz/store', [WebQuizController::class, 'storeQuiz']);
    Route::get('/history/{user_id}', [WebQuizController::class, 'getQuizByUserId']);
    Route::get('/review/{id}', [ReviewController::class, 'getQuizReview']);
});

// ✅ Fallback Route (Handles 404 errors)
Route::fallback([FallbackController::class, 'index'])->name('404');