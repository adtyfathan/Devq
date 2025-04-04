<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplayerSession extends Model
{
    use HasFactory;

    protected $table = 'multiplayer_session'; 

    protected $fillable = ['host_id', 'quiz_id', 'session_code', 'status', 'started_at', 'ended_at'];

    protected $casts = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public $timestamps = false;
    
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function quizTemplate()
    {
        return $this->belongsTo(MultiplayerSession::class, 'quiz_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'multiplayer_user')
                    ->withPivot(['username', 'point', 'joined_at', 'completed_at'])
                    ->withTimestamps();
    }
}