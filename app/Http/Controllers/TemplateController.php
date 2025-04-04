<?php

namespace App\Http\Controllers;

use App\Models\QuizTemplate;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function storeTemplate(Request $request){
        try {
            $validated = $request->validate([
                'category' => 'required|string',
                'difficulty' => 'required|string'
            ]);

            $template = QuizTemplate::create([
                'category' => $validated['category'],
                'difficulty' => $validated['difficulty']
            ]);

            return response()->json(["message" => "Quiz template successfully created", "data" => $template], 201);
            
        }  catch (Exception $e) {
            Log::error('Error in store method', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}