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

    public function getQuizReview($id)
    {
        $quiz = Quiz::select('id', 'score', 'category', 'difficulty', 'completed_at')
            ->with([
                'questions' => function ($query) {
                    $query->select(
                            'questions.id',
                            'questions.question',
                            'questions.description',
                            'questions.answers',
                            'questions.correct_answers',
                            'questions.explanation'
                        )
                        ->withPivot('user_answer');
                }
            ])
            ->find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $reviewData = [
            'quiz_id' => $quiz->id,
            'score' => $quiz->score,
            'category' => $quiz->category,
            'difficulty' => $quiz->difficulty,
            'completed_at' => $quiz->completed_at,
            'questions' => $quiz->questions->map(fn($q) => [
                'question_id' => $q->id,
                'question' => $q->question,
                'description' => $q->description,
                'answers' => $q->answers,
                'correct_answers' => $q->correct_answers,
                'explanation' => $q->explanation,
                'user_answer' => $q->pivot->user_answer,
            ]),
        ];

        return response()->json($reviewData, 200);
}
}