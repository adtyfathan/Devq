<?php

namespace App\Http\Controllers;

use App\Models\MultiplayerSession;
use App\Services\QuizService;
use Illuminate\Http\Request;

class MultiplayerController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService){
        $this->quizService = $quizService;
    }
    
    public function createLobby(Request $request, $category){
        $difficulty = $request->query('difficulty', 'hard');
        $limit = $request->query('limit', 10);

        $questions = $this->quizService->fetchQuestions($category, $difficulty, $limit);

        // generate session code
        return response()->json(['message' => 'Quiz template successfully created', 'questions' => $questions], 200);
    }

    public function showHostView(Request $request, $category){       
        $difficulty = $request->query('difficulty', 'easy'); 
        $limit = $request->query('limit', 10); 
        
        return view('quiz.lobby-host', compact('category', 'difficulty', 'limit'));
    }


    public function getLobbyDetail($lobby_id){
        $lobby = MultiplayerSession::where("session_code", "=",  $lobby_id)->first();

        return $lobby
            ? response()->json(['success' => true, 'lobby' => $lobby])
            : response()->json(['success' => false, 'message' => 'Lobby not found!'], 404);
    }
    
    public function showPlayerView($lobby_id){
        $lobby = MultiplayerSession::where("session_code", "=",  $lobby_id)->first();
        
        if (!$lobby) {
            // error redirect ke 404
            return redirect()->route('404');
        }
        
        return view('quiz.lobby-player');
    }
}