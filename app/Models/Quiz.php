<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\QuizQuestion; 
use App\Models\Question;

class Quiz extends Model
{
     use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'score',
        'completed_at',
        'category',
        'difficulty',
        'user_answer'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'user_answer' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
                    ->withPivot(['user_answer'])
                    ->withTimestamps();
    }

    public function multiplayerSessions()
    {
        return $this->hasMany(MultiplayerSession::class);
    }
}

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

// public function creator(): BelongsTo
// {
//     return $this->belongsTo(User::class, 'user_id');
// }

// public function multiplayerSessions(): HasMany
// {
//     return $this->hasMany(MultiplayerSession::class, 'quiz_id');
// }

// public function players(): BelongsToMany
// {
//     return $this->belongsToMany(User::class, 'multiplayer_user', 'multiplayer_session_id', 'user_id')
//         ->withPivot('joined_at', 'completed_at', 'point')
//         ->withTimestamps();
// }