<?php

namespace App\Http\Controllers;

use App\Models\QuizTemplate;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function storeTemplate(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|string|max:255',
                'difficulty' => 'required|string|max:255',
                'question_count' => 'required'
            ]);

            $template = QuizTemplate::create($validated);

            return response()->json([
                "message" => "Quiz template successfully created",
                "data" => $template
            ], 201);

        } catch (Exception $e) {
            Log::error('Error in storeTemplate method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}