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

    public function fetchQuestions($category = null, $difficulty = null, $limit = 10)
    {
        $params = [
            'limit' => $limit,
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

        return $response->successful() ? $response->json() : [];
    }
}