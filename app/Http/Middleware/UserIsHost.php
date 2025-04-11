<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\MultiplayerSession;
use Illuminate\Support\Facades\DB;

class UserIsHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionCode = $request->route('session_code');
        $userId = Auth::id();

        $isHost = DB::table('multiplayer_session')
            ->where('session_code', $sessionCode)
            ->where('host_id', $userId)
            ->exists();

        if (!$isHost) {
            return response()->json([
                'message' => 'Unauthorized. You are not the host of this session.'
            ], 403);
        }

        return $next($request);
    }
}