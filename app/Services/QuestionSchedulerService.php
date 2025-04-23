<?php

namespace App\Services;
use App\Models\MultiplayerSession;
use App\Services\QuizService;
use App\Models\MultiplayerUser;
use App\Jobs\BroadcastQuestion;
use App\Jobs\BroadcastStandings;

class QuestionSchedulerService{
    public function __construct(protected QuizService $quizService){
        
    }
    
    public function start(MultiplayerSession $session){
        $session = MultiplayerSession::with('quizTemplate')->find($session->id);
        $category = $session->quizTemplate->category;
        $difficulty = $session->quizTemplate->difficulty;
        $questionCount = $session->quizTemplate->question_count;
            
        $questions = $this->quizService->fetchQuestions($category, $difficulty, $questionCount);
        
        // define durasi per section
        $openingDuration = 5;
        $questionDuration = 15;
        $memeDuration = 5;
        $standingsDuration = 10;

        // tambahin waktu buat user load halaman pertama kali
        $startTime = now()->addSeconds(3);
        
        foreach ($questions as $index => $question) {
            $openingAt = $startTime->copy()->addSeconds(($openingDuration + $questionDuration + $memeDuration + $standingsDuration) * $index);
            $questionAt = $openingAt->copy()->addSeconds($openingDuration);
            $memeAt = $questionAt->copy()->addSeconds($questionDuration);
            $standingsAt = $memeAt->copy()->addSeconds($memeDuration);

            BroadcastQuestion::dispatch(
                $session, 
                $question, 
                $openingAt, 
                $questionAt, 
                $memeAt
            )->delay(max(0, now()->diffInSeconds($openingAt)));

            $players = MultiplayerUser::where('multiplayer_session_id', $session->id)
                ->orderByDesc('point')
                ->get();

            BroadcastStandings::dispatch($session, $players, $standingsAt)
                ->delay(max(0, now()->diffInSeconds($standingsAt)));
        }
    }
}