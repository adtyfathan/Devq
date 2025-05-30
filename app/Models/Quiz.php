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
        'difficulty'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
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
}