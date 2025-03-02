<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function loginForm(){
        return view("auth.login");
    }

    public function registerForm(){
        return view("auth.register");
    }
    
    public function login(Request $request){
        
    }

    public function register(Request $request){
        
    }

    public function logout(){
        
    }
}