<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['id', 'question', 'description', 'answers', 'correct_answer'];
    
    protected $casts = [
        'answers' => 'array',
    ];

}