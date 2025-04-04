<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'question',
        'category',
        'difficulty',
        'description',
        'answers', 
        'correct_answers',
        'explanation'
        
    ];

    protected $casts = [
        'answers' => 'array', 
        'correct_answers' => 'array'
    ];

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
                    ->withPivot(['user_answer'])
                    ->withTimestamps();
    }

    public function quizTemplate()
    {
        return $this->belongsToMany(MultiplayerSession::class, 'quiz_questions')
                    ->withPivot(['user_answer'])
                    ->withTimestamps();
    }
}