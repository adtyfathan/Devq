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
                    ->withTimestamps();
    }
}