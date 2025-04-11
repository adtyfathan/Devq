<?php

use App\Models\MultiplayerSession;
use App\Models\MultiplayerUser;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('multiplayer.{sessionId}', function ($user, $sessionId) {
    $session = MultiplayerSession::find($sessionId);

    if (!$session) return false;

    if ($session->host_id === $user->id) {
        return true;
    }

   return MultiplayerUser::where('multiplayer_session_id', $sessionId)
        ->where('user_id', $user->id)
        ->exists();
});