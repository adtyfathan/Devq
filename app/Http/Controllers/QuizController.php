<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function createQuizView(){
        return view('quiz.create');
    }

    public function storeQuiz(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'score' => 'required|numeric|min:0|max:100',
                'category' => 'required|string',
                'difficulty' => 'required|string',
                'user_answer' => 'required|array',
                'questions' => 'required|array'
            ]);

            if (count($validated['user_answer']) !== count($validated['questions'])) {
                return response()->json(['error' => 'Mismatched question and answer count'], 400);
            }
            
            $questionIds = [];
            
            foreach ($validated['questions'] as $question){
                Question::firstOrCreate(
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
                array_push($questionIds, $question['id']);
            }
            
            $quiz = Quiz::create([
                'user_id' => $validated['user_id'],
                'score' => $validated['score'],
                'category' => $validated['category'],
                'completed_at' => Carbon::now(),
                'difficulty' => $validated['difficulty']
            ]);

            // buat scenario buat input quiz dari template
            for($i = 0; $i < count($validated['user_answer']); $i++){
                $quiz->questions()->attach($questionIds[$i], ['user_answer' => $validated['user_answer'][$i]]);
            }

            DB::commit();

            return response()->json(['id' => $quiz->id], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuizById($id)
    {
        try {
            $quiz = DB::table('quizzes')->where('id', $id)->first();

            if (!$quiz) {
                return response()->json(['error' => 'No Quiz found'], 404);
            }

            return response()->json($quiz, 200);
        } catch (Exception $e) {
            Log::error('Error in getQuizById method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getQuizByUserId($user_id)
    {
        try {
            $quizzes = DB::table('quizzes')
                ->where('user_id', $user_id)
                ->orderByDesc('completed_at')
                ->get();

            if ($quizzes->isEmpty()) {
                return response()->json(['error' => 'No quizzes found'], 404);
            }

            return response()->json($quizzes, 200);
        } catch (Exception $e) {
            Log::error('Error in getQuizByUserId method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}