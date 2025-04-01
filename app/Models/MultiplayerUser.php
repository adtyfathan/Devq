<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplayerUser extends Model
{
    use HasFactory;

    protected $table = 'multiplayer_user';

    protected $fillable = ['multiplayer_session_id', 'user_id', 'username', 'point', 'joined_at', 'completed_at'];

    public function session()
    {
        return $this->belongsTo(MultiplayerSession::class, 'multiplayer_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}