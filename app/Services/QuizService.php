<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class QuizService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.quizapi.url');
        $this->apiKey = config('services.quizapi.key');
    }

    public function fetchQuestions($category = null, $difficulty = null, $page = 1, $perPage = 10)
    {
        $params = [
            'limit' => 20 // sementara buat testing
        ];

        if ($category) {
            $params['category'] = $category;
        }

        if ($difficulty) {
            $params['difficulty'] = $difficulty;
        }

        $response = Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ])->get($this->apiUrl, $params);

        if (!$response->successful()) {
            return [];
        }

        // Get API response as an array
        $questions = $response->json();

        return [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => count($questions), 
            'last_page' => ceil(count($questions) / $perPage),
            'data' => $questions, // Directly return the filtered questions
        ];
    }
}