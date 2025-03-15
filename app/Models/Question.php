<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'question',
        'description',
        'answers',
        'correct_answer',
    ];

    protected $casts = [
        'answers' => 'array', // Store JSON as an array
    ];

    /**
     * The quizzes that include this question.
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')->withTimestamps();
    }
}