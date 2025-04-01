<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MultiplayerUser extends Pivot
{
   use HasFactory;

    protected $table = 'multiplayer_user';

    protected $fillable = ['multiplayer_session_id', 'user_id', 'username', 'point', 'joined_at', 'completed_at'];

    protected $casts = ['joined_at' => 'datetime', 'completed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function multiplayerSession()
    {
        return $this->belongsTo(MultiplayerSession::class);
    }
}