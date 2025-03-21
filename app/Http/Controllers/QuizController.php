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
    public function store(Request $request)
    {
        try {
            Log::info('Store method started', ['request' => $request->all()]);

            // Validate request
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'score' => 'required|integer|min:0',
                'category' => 'required|string',
                'difficulty' => 'required|string',
                'user_answer' => 'required|array',
                'questions' => 'required|array'
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            // Create and save quiz
            $quiz = new Quiz();
            $quiz->user_id = $validated['user_id'];
            $quiz->score = $validated['score'];
            $quiz->category = $validated['category'];
            $quiz->completed_at = Carbon::now();
            $quiz->difficulty = $validated['difficulty'];
            $quiz->user_answer = json_encode($validated['user_answer']); // Ensure it's stored as JSON

            $quiz->save();

            foreach ($validated['questions'] as $question){
                $question = Question::updateOrCreate(
                  ['id' => $question['id']],
                   [
                    'question' => $question['question'],
                    'category' => $question['category'],
                    'difficulty' => $question['difficulty'],
                    'description' => $question['description'],
                    'answers' => json_encode($question['answers']), // Ensure JSON encoding
                    'correct_answers' => json_encode($question['correct_answers']),
                    'explanation' => $question['explanation']
                ]
                );
            }

            Log::info('Quiz saved', ['quiz' => $quiz]);

            // Attach questions (debugging inside loop)
            foreach ($validated['user_answer'] as $answer) {
                if (!isset($answer['question_id'])) {
                    Log::error('Missing question_id', ['answer' => $answer]);
                    return response()->json(['error' => 'Invalid user_answer format'], 400);
                }

                Log::info('Attaching question', ['question_id' => $answer['question_id']]);
                $quiz->questions()->attach($answer['question_id']);
            }

            Log::info('All questions attached successfully');

            return response()->json($quiz, 201);

        } catch (Exception $e) {
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}