<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class FetchQuizQuestions extends Command
{
    protected $signature = 'fetch:quiz-questions';
    protected $description = 'Fetch all quiz questions from the API and store them in the database';

    public function handle()
    {
        $apiUrl = config('services.quizapi.url');
        $apiKey = config('services.quizapi.key');

        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $questions = $response->json();
            foreach ($questions as $question) {
                Question::updateOrCreate(
                    ['id' => $question['id']],
                    [
                        'question' => $question['question'],
                        'description' => $question['description'] ?? 'No description available',
                        'answers' => json_encode($question['answers']),
                        'correct_answer' => $this->getCorrectAnswerIndex($question['correct_answers']),
                    ]
                );
            }
            $this->info('Questions fetched and stored successfully!');
        } else {
            // Log the response status and error details
            $errorMessage = $response->body(); // Get the full error response
            Log::error("API Request Failed: {$response->status()} - {$errorMessage}");
            
            $this->error("Failed to fetch questions. Status: {$response->status()}");
            $this->error("Error Details: {$errorMessage}");
        }
    }


    private function getCorrectAnswerIndex($correct_answers)
    {
        $keys = ['answer_a_correct', 'answer_b_correct', 'answer_c_correct', 'answer_d_correct', 'answer_e_correct', 'answer_f_correct'];

        foreach ($keys as $index => $key) {
            if (isset($correct_answers[$key]) && $correct_answers[$key] === "true") {
                return $index;
            }
        }

        return null;
    }
}