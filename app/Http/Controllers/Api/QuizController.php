<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QuizService;

class QuizController extends Controller
{
   protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function showQuiz($category = null, $difficulty = null)
    {
        $category = $category ?? 'Code';   // Default category
        $difficulty = $difficulty ?? 'Easy';  // Default difficulty
        
        $questions = $this->quizService->fetchQuestions($category, $difficulty);
        return view('quiz.index', compact('questions')); 
    }
}

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Quiz;
// use Illuminate\Http\Request;
// use App\Http\Resources\QuizResource;

// class QuizController extends Controller
// {
//     public function index()
//     {
//         return QuizResource::collection(Quiz::all());
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'title' => 'required|string',
//             'description' => 'nullable|string',
//         ]);

//         $quiz = Quiz::create($request->all());

//         return new QuizResource($quiz);
//     }
// }