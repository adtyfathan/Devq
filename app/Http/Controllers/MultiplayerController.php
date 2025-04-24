<?php

namespace App\Http\Controllers;

use App\Events\CreateMultiplayerLobby;
use App\Events\LeaveMultiplayerLobby;
use App\Events\QuizStarted;
use App\Models\MultiplayerSession;
use App\Models\MultiplayerUser;
use App\Models\User;
use App\Services\QuizService;
use App\Services\QuestionSchedulerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MultiplayerController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService){
        $this->quizService = $quizService;
    }

    public function showMultiplayerQuiz(){
        return view('quiz.multiplayer');
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
            } while (DB::table('multiplayer_session')->where('session_code', $sessionCode)->exists());
            
            $session = MultiplayerSession::create([
                'host_id' => $validated['host_id'],
                'quiz_id' => $validated['quiz_id'],
                'session_code' => $sessionCode,
                'status' => $validated['status']
            ]);

            return response()->json(['message' => 'Session created', 'data' => $session], 201);
            
        } catch (Exception $e){
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function createMultiplayerUser(Request $request){
        $validated = $request->validate([
            'session_id' => 'required|exists:multiplayer_session,id',
            'player_id' => 'required|exists:users,id',
            'username' => 'required'
        ]);
        
        try {
            // session
            $session = MultiplayerSession::with('host')->findOrFail($validated['session_id']);
            $user = User::findOrFail($validated['player_id']);

            $session->users()->attach($user->id, [
                'username' => $validated['username'],
                'point' => 0,
                'joined_at' => now()
            ]);

            // host
            $host = $session->host;

            // player
            $player = MultiplayerUser::where([
                ['multiplayer_session_id', '=', $session->id],
                ['user_id', '=', $user->id],
            ])->latest('id')->first();

            broadcast(new CreateMultiplayerLobby($session, $host, $player));
            
            return response()->json(['message' => 'Player added successfully', 'data' => $player], 201);
        } catch (Exception $e) {
            Log::error('Error in createMultiplayerUser method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function leaveMultiplayerUser(Request $request){
        $validated = $request->validate([
            'session_id' => 'required|exists:multiplayer_session,id',
            'player_id' => 'required|exists:users,id'
        ]);

        try {
            $session = MultiplayerSession::with('host')->findOrFail($validated['session_id']);
            $user = User::findOrFail($validated['player_id']);
            $host = $session->host;

            if ($session->users()->where('user_id', $user->id)->exists()) {
                $session->users()->detach($user->id);
            }

            MultiplayerUser::where([
                ['multiplayer_session_id', '=', $session->id],
                ['user_id', '=', $user->id],
            ])->delete();

            broadcast(new LeaveMultiplayerLobby($session, $host, $user));

            return response()->json(['message' => 'Player left the lobby'], 200);
        } catch (Exception $e) {
            Log::error('Error in leaveMultiplayerUser method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuizSessionByPlayerId($playerId)
    {
        try {
            $player = MultiplayerUser::with([
                    'multiplayerSession:id,quiz_id,status,host_id,session_code', 
                    'multiplayerSession.quizTemplate:id,category,difficulty'
                ])
                ->where('user_id', $playerId)
                ->whereNull('completed_at')
                ->latest('joined_at')
                ->first();

            if (!$player) {
                return response()->json(['message' => 'No ongoing quiz found'], 404);
            }

            return response()->json([
                'message' => 'Quiz session retrieved',
                'data' => $player
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in getQuizSessionByPlayerId method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function showHostView($sessionCode){   
        $session = MultiplayerSession::where('session_code', '=', $sessionCode)->first();
        
        if(!$session){
            return view('404', ['error' => 'Session not found']);
        }
        
        return view('quiz.lobby-host');
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

    public function getPlayersBySessionId($sessionId){
        $players = DB::table('multiplayer_user')->where('multiplayer_session_id', $sessionId)->get();

        if ($players->isEmpty()) {
            return response()->json(['message' => 'No players found in session'], 404);
        }

        return response()->json(['message' => 'Players retrieved', 'data' => $players], 200);
    }

    public function deleteUserSessionByUserId($userId){
        
    }

    public function startMultiplayerQuiz(Request $request){
        try {
            $session = MultiplayerSession::find($request->session_id);

            if ($session->status !== 'waiting') {
                return response()->json(['message' => 'Quiz already started.'], 400);
            }

            $session->update(['status' => 'in_progress']);

            // Broadcast to notify all players to redirect
            broadcast(new QuizStarted($session));

            // Start sending questions
            app(QuestionSchedulerService::class)->start($session);

            return response()->json(['message' => 'Quiz started.']);
        } catch (Exception $e) {
            Log::error('Error in startMultiplayerQuiz method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function handlePlayerAnswer(Request $request){
        try {
            $validated = $request->validate([
                'multiplayer_session_id' => 'required|exists:multiplayer_session,id',
                'player_id' => 'required|exists:users,id',
                'point' => 'required|integer',
                'answer' => 'nullable|string',
                'is_correct' => 'required|boolean'
            ]);

            $cacheKey = "quiz_session:{$validated['multiplayer_session_id']}:user:{$validated['player_id']}:answers";
            $answers = Cache::get($cacheKey, []);

            $answers[] = [
                'answer' => $validated['answer'], // nullable OK
                'is_correct' => $validated['is_correct'],
                'point' => $validated['point'],
                'time' => now()->toDateTimeString()
            ];

            Cache::put($cacheKey, $answers, now()->addMinutes(15));

            if(!$validated['is_correct']){
                return response()->json(['message' => 'Answer saved'], 200);
            }

            $current = DB::table('multiplayer_user')
                ->where('user_id', $validated['player_id'])
                ->where('multiplayer_session_id', $validated['multiplayer_session_id'])
                ->first();

            if (!$current) {
                return response()->json(['message' => 'Player not found in session'], 404);
            }

            $newPoint = $current->point + $validated['point'];

            $playerQuiz = DB::table('multiplayer_user')
                ->where('user_id', $validated['player_id'])
                ->where('multiplayer_session_id', $validated['multiplayer_session_id'])
                ->update(['point' => $newPoint]);
            
            if(!$playerQuiz){
                return response()->json(['message' => 'Error updating player point'], 400);
            }

            return response()->json(['message' => 'Point updated', 'data' => $playerQuiz], 200);
        } catch (Exception $e) {
            Log::error('Error in updatePoint method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function getUserAnswers($sessionId, $playerId){
        $cacheKey = "quiz_session:{$sessionId}:user:{$playerId}:answers";
        $answers = Cache::get($cacheKey, []);

        return response()->json(['message' => 'User answers retrieved', 'data' => $answers]);
    }
}