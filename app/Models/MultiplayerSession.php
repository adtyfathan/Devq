<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplayerSession extends Model
{
    use HasFactory;

    protected $table = 'multiplayer_session'; 

    protected $fillable = ['host_id', 'quiz_id', 'session_code', 'status', 'started_at', 'ended_at'];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function players()
    {
        return $this->hasMany(MultiplayerUser::class, 'multiplayer_session_id');
    }
}