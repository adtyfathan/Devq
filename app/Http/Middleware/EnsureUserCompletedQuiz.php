<?php

namespace App\Http\Middleware;

use App\Models\Quiz;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $userId = Auth::id();

        $quizExists = DB::table('quizzes')
            ->where('id', $summaryId)
            ->where('user_id', $userId)
            ->exists();
        
        if (!$quizExists) {
            return response()->json([
                'message' => 'Unauthorized. You have not completed this quiz.'
            ], 403);
        }
        
        return $next($request);
    }
}