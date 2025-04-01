<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'point',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all quizzes taken by the user.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function hostedSessions()
    {
        return $this->hasMany(MultiplayerSession::class, 'host_id');
    }

    public function multiplayerSessions()
    {
        return $this->belongsToMany(MultiplayerSession::class, 'multiplayer_user')
                    ->withPivot(['username', 'point', 'joined_at', 'completed_at'])
                    ->withTimestamps();
    }
}

// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// public function createdQuizzes(): HasMany
// {
//     return $this->hasMany(Quiz::class, 'user_id');
// }

// public function playedQuizzes(): BelongsToMany
// {
//     return $this->belongsToMany(Quiz::class, 'multiplayer_user', 'user_id', 'multiplayer_session_id')
//         ->withPivot('joined_at', 'completed_at', 'point')
//         ->withTimestamps();
// }