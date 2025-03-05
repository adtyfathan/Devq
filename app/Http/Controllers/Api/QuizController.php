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

    public function getQuestions($category = null, $difficulty = null, $page = 1, $perPage = 10)
    {
        $questions = $this->quizService->fetchQuestions($category, $difficulty, $page, $perPage);

       return view('quiz.index', ['questions' => $questions]);
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