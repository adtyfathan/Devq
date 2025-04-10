<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MultiplayerUser;

class PlayerCanJoinSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->input('player_id');
        $sessionId = $request->input('session_id');
        
        $alreadyJoined = MultiplayerUser::where('user_id', $userId)
            ->where('multiplayer_session_id', $sessionId)
            ->exists();

        if($alreadyJoined){
            return response()->json([
                'message' => 'You already joined this quiz'
            ], 403);
        }

        $inOtherSession = MultiplayerUser::where('user_id', $userId)
            ->whereNull('completed_at')
            ->where('multiplayer_session_id', '!=', $sessionId)
            ->exists();

        if($inOtherSession){
            return response()->json([
                'message' => 'You already joined in another quiz season'
            ], 403);
        }

        return $next($request);
    }
}