<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class QuizController extends Controller
{
    public function storeQuiz(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'score' => 'required|numeric|min:0|max:100',
                'category' => 'required|string',
                'difficulty' => 'required|string',
                'user_answer' => 'required|array',
                'questions' => 'required|array'
            ]);

            Log::info('Validation passed', ['validated' => $validated]);
            
            foreach ($validated['questions'] as $question){
                $question = Question::firstOrCreate(
                  ['id' => $question['id']],
                   [
                    'question' => $question['question'],
                    'category' => $question['category'],
                    'difficulty' => $question['difficulty'],
                    'description' => $question['description'],
                    'answers' => json_encode($question['answers']),
                    'correct_answers' => json_encode($question['correct_answers']),
                    'explanation' => $question['explanation']
                ]
                );
            }
            
            $quiz = Quiz::create([
                'user_id' => $validated['user_id'],
                'score' => $validated['score'],
                'category' => $validated['category'],
                'completed_at' => Carbon::now(),
                'difficulty' => $validated['difficulty'],
                'user_answer' => json_encode($validated['user_answer']),
            ]);

            Log::info('Quiz saved', ['quiz' => $quiz]);


            foreach ($validated['user_answer'] as $answer) {
                if (!isset($answer['question_id'])) {
                    Log::error('Missing question_id', ['answer' => $answer]);
                    return response()->json(['error' => 'Invalid user_answer format'], 400);
                }

                Log::info('Attaching question', ['question_id' => $answer['question_id']]);
                $quiz->questions()->attach($answer['question_id']);
            }

            Log::info('All questions attached successfully');

            return response()->json(['id' => $quiz->id], 201);

        } catch (Exception $e) {
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuizById($id){
        try {
            $quiz = Quiz::find($id);

            if (!$quiz) {
                return response()->json(['error' => 'No Quiz found'], 404);
            }

            return response()->json($quiz, 200);
        } catch(Exception $e){
            Log::error('Error in getQuizById method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuizByUserId($user_id){
        try {
            $quiz = Quiz::where('user_id', $user_id)->get();
            
            if ($quiz->isEmpty()) {
                return response()->json(['error' => 'No quizzes found'], 404);
            }

            return response()->json($quiz, 200);
        } catch (Exception $e){
            Log::error('Error in getQuizByUserId method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}

// 0 1 2 3 | 4