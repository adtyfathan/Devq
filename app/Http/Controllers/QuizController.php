<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        Log::info('Debugging Quiz:', ['data' => $quiz]);

        $quizQuestionData = [];

        foreach ($validated['user_answer'] as $answer) {
            if (isset($answer['question_id'])) {
                $quizQuestionData[] = [
                    'quiz_id' => $quiz->id,
                    'question_id' => $answer['question_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            } 
        }

        if (!empty($quizQuestionData)) {
            QuizQuestion::insert($quizQuestionData); 
        } 

        return response()->json($quiz, 201);
    }
}