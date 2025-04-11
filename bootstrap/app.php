<?php

use App\Http\Middleware\EnsureUserCompletedQuiz;
use App\Http\Middleware\PlayerCanJoinSession;
use App\Http\Middleware\UserIsHost;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'canJoin' => PlayerCanJoinSession::class,
            'isHost' => UserIsHost::class,
            'ensureCompletedQuiz' => EnsureUserCompletedQuiz::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();