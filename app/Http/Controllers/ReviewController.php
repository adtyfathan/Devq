<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request){
        $id = $request->query('id');
        
        if(!$id){
            return view('quiz.review', ['error' => 'No ID provided']);
        }

        return view('quiz.review');
    }

    public function getQuizReview($id){
        $quiz = Quiz::with(['questions' => function ($query) {
            $query->select('questions.id', 'questions.question', 'questions.description', 'questions.answers', 'questions.correct_answers', 'questions.explanation')
                ->withPivot('user_answer');
        }])->find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        return response()->json([
            'quiz_id' => $quiz->id,
            'score' => $quiz->score,
            'category' => $quiz->category,
            'difficulty' => $quiz->difficulty,
            'completed_at' => $quiz->completed_at,
            'questions' => $quiz->questions->map(function ($question) {
                return [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'description' => $question->description,
                    'answers' => $question->answers,
                    'correct_answers' => $question->correct_answers,
                    'explanation' => $question->explanation,
                    'user_answer' => $question->pivot->user_answer,
                ];
            }),
        ], 200);
    }
}