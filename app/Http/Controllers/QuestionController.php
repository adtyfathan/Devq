<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function getQuestionById($id){
        try {
            $question = Question::find($id);

            if (!$question) {
                return response()->json(['error' => 'No Question found'], 404);
            }

            return response()->json($question, 200);
        } catch(Exception $e){
            Log::error('Error in getQuestionById method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}