<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('multiplayer.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});