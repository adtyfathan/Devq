<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(Request $request){
        $id = $request->query('id');

        if (!$id) {
            return view('quiz.summary', ['error' => 'No summary ID provided.']);
        }
        
        return view('quiz.summary');
    }

    public function getSummary($id){ 
        $summary = Quiz::find($id);

        if (!$summary) {
            return response()->json(['error' => 'No summary found'], 404);
        }

        return response()->json($summary, 200);
    }
}