<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
   protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function showQuiz(Request $request, $category)
    {
        $difficulty = $request->query('difficulty', 'easy'); 
        $limit = $request->query('limit', 10); 

        return view('quiz.index', compact('category', 'difficulty', 'limit'));
    }

    // HAPUS SESSION PAS QUIZ SELESAI
    
    public function getQuestions(Request $request)
    {
        $category = $request->query('category', null);
        $difficulty = $request->query('difficulty', 'easy'); 
        $limit = $request->query('limit', 10);
        
        if (!$category) {
            return response()->json(["error" => "Category is required"], 400);
        }
        
        $questions = $this->quizService->fetchQuestions($category, $difficulty, $limit);

        return response()->json(["questions" => $questions]);
    }
}