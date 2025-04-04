<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'category',
        'difficulty'
    ];

    public function multiplayerSession(){
        return $this->hasMany(MultiplayerSession::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
                    ->withPivot(['user_answer'])
                    ->withTimestamps();
    }
}