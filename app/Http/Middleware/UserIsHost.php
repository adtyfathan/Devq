<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\MultiplayerSession;

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
        $user = Auth::user();

        $session = MultiplayerSession::where('session_code', $sessionCode)->first();

        if (!$session || $session->host_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You are not the host of this session.'], 403);
        }

        return $next($request);
    }
}