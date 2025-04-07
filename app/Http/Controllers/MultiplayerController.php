<?php

namespace App\Http\Controllers;

use App\Events\CreateMultiplayerLobby;
use App\Models\MultiplayerSession;
use App\Models\MultiplayerUser;
use App\Models\User;
use App\Services\QuizService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MultiplayerController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService){
        $this->quizService = $quizService;
    }
    
    // ganti nama jadi getQuestions
    public function createLobby(Request $request, $category){
        $difficulty = $request->query('difficulty', 'hard');
        $limit = $request->query('limit', 10);

        $questions = $this->quizService->fetchQuestions($category, $difficulty, $limit);

        return response()->json(['message' => 'Quiz template successfully created', 'questions' => $questions], 200);
    }

    public function createSession(Request $request){
        try {
            $validated = $request->validate([
                'host_id' => 'required|exists:users,id',
                'quiz_id' => 'required|exists:quiz_templates,id',
                'status' => 'required|string'
            ]);

            do {
                $sessionCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (MultiplayerSession::where('session_code', $sessionCode)->exists());

            $sessionCodeStr = (string)$sessionCode;
            
            $session = MultiplayerSession::create([
                'host_id' => $validated['host_id'],
                'quiz_id' => $validated['quiz_id'],
                'session_code' => $sessionCodeStr,
                'status' => $validated['status']
            ]);

            return response()->json(['message' => 'Session created', 'data' => $session], 201);
        } catch (Exception $e){
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSessionState(){
        // start_at quiz pas quiz mulai by host
        // ended_at quiz pas session code quiz berakhir
    }

    public function createMultiplayerUser(Request $request){
        $validated = $request->validate([
            'session_id' => 'required|exists:multiplayer_session,id',
            'player_id' => 'required|exists:users,id',
            'username' => 'required'
        ]);

        // session
        $session = MultiplayerSession::findOrFail($validated['session_id']);

        // host
        $hostId = $session->host_id;
        $host = User::find($hostId);

        $user = User::findOrFail($validated['player_id']);

        $session->users()->attach($user->id, [
            'username' => $validated['username'],
            'point' => 0,
            'joined_at' => Carbon::now()
        ]);

        // player
        $player = MultiplayerUser::where('multiplayer_session_id', $session->id)
            ->where('user_id', $user->id)
            ->first();

        broadcast(new CreateMultiplayerLobby($session, $host, $player));

        return response()->json(['message' => 'Player added succesfully'], 201);
    }

    public function showHostView(Request $request, $category){       
        $difficulty = $request->query('difficulty', 'easy'); 
        $limit = $request->query('limit', 10); 
        
        return view('quiz.lobby-host', compact('category', 'difficulty', 'limit'));
    }


    public function getLobbyDetail($lobby_id){
        $lobby = MultiplayerSession::where("session_code", "=",  $lobby_id)
        ->where("status", "=", "waiting")
        ->first();

        return $lobby
            ? response()->json(['success' => true, 'lobby' => $lobby])
            : response()->json(['success' => false, 'message' => 'Lobby not found!'], 404);
    }
    
    public function showPlayerView($lobby_id){
        $lobby = MultiplayerSession::where("session_code", "=",  $lobby_id)->first();
        
        if (!$lobby) {
            return redirect()->route('404');
        }
        
        return view('quiz.lobby-player');
    }

    public function getSessionIdBySessionCode($sessionCode){
        $session = MultiplayerSession::where('session_code', '=', $sessionCode)->first();
        
        if (!$session){
            return response()->json(['message' => 'Session not found'], 404);
        }
        
        return response()->json(['message' => 'Session found', 'data' => $session], 200);
    }
}