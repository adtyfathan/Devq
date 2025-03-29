<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request){
        $id = $request->query('id');
        
        if(!$id){
            return view('quiz.review', ['error' => 'No ID provided']);
        }

        return view('quiz.review');
    }
}