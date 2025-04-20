<?php

namespace App\Services;
use App\Models\MultiplayerSession;
use App\Services\QuizService;
use App\Events\QuestionBroadcasted;
use App\Events\StandingsUpdated;
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
        $questionDuration = 15;
        $standingsDuration = 5;
        $startTime = now();

        
        
        foreach ($questions as $index => $question) {
            $questionAt = $startTime->copy()->addSeconds(($questionDuration + $standingsDuration) * $index);
            $standingsAt = $questionAt->copy()->addSeconds($questionDuration);

            BroadcastQuestion::dispatch($session, $question, $questionAt)
                ->delay(max(0, now()->diffInSeconds($questionAt)));

            $players = MultiplayerUser::where('multiplayer_session_id', $session->id)
                ->orderByDesc('point')
                ->get();

            BroadcastStandings::dispatch($session, $players, $standingsAt)
                ->delay(max(0, now()->diffInSeconds($standingsAt)));

            // dispatch(function () use ($session, $standingsAt) {
            //     $players = MultiplayerUser::where('multiplayer_session_id', $session->id)
            //         ->orderByDesc('point')
            //         ->get();

            //     broadcast(new StandingsUpdated($session, $players, $standingsAt));
            // })->delay($standingsAt->diffInSeconds(now()));
        }
    }
}