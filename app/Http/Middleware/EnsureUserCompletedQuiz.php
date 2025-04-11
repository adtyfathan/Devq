<?php

namespace App\Http\Middleware;

use App\Models\Quiz;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserCompletedQuiz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $summaryId = $request->input('id');
        $user = Auth::user();

        $quiz = Quiz::find($summaryId);
        
        if(!$quiz || $quiz->user_id !== $user->id){
            return response()->json(['message' => 'Unauthorized. You are not completed this quiz.'], 403);
        }
        
        return $next($request);
    }
}