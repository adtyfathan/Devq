<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get("/login", [AuthController::class, "loginForm"])->name("login");

Route::get("/register", [AuthController::class, "registerForm"])->name("register");

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, "index"])->name("home");
});

Route::fallback([FallbackController::class, "index"])->name("404");