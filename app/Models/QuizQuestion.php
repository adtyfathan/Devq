<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuizQuestion extends Pivot
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $fillable = ['quiz_id', 'question_id', 'user_answer'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}