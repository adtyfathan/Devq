<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Quiz;
// use Illuminate\Http\Request;
// use App\Http\Resources\QuizResource;

// class QuizController extends Controller
// {
//     public function index()
//     {
//         return QuizResource::collection(Quiz::all());
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'title' => 'required|string',
//             'description' => 'nullable|string',
//         ]);

//         $quiz = Quiz::create($request->all());

//         return new QuizResource($quiz);
//     }
// }