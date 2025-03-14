<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'score' => 'required|integer|min:0',
            'category' => 'required|string',
            'difficulty' => 'required|string',
            'user_answer' => 'required|array'
        ]);

        $quiz = Quiz::create([
           'user_id' => $validated['user_id'],
           'score' => $validated['score'],
           'category' => $validated['category'],
           'completed_at' => Carbon::now(),
           'difficulty' => $validated['difficulty'],
           'user_answer' => $validated['user_answer']
        ]);

        return response()->json($quiz, 201);
    }
}