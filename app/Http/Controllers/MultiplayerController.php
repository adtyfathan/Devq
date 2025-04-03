<?php

namespace App\Http\Controllers;

use App\Models\MultiplayerSession;
use Illuminate\Http\Request;

class MultiplayerController extends Controller
{
    public function createLobby(Request $request){
       
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