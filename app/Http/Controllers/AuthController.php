<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm(){
        return view("auth.login");
    }

    public function registerForm(){
        return view("auth.register");
    }
    
    public function login(Request $request){
        // ✅ Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // ✅ Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan'])->onlyInput('email');
        }

        // ✅ Attempt login
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            return back()->withErrors(['password' => 'Password salah'])->onlyInput('email');
        }

        // ✅ Secure session
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function register(Request $request){
        // ✅ Validate user input with optimized rules
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => [
            'required',
            'string',
            'min:8',              // Minimum 8 characters
            'regex:/[A-Z]/',      // At least one uppercase letter
            'regex:/[a-z]/',      // At least one lowercase letter
            'regex:/[0-9]/',      // At least one number
            'confirmed',          // Must match password_confirmation
        ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.'
        ]);

        // ✅ Securely hash password before storing
        $validated['password'] = Hash::make($validated['password']);

        // ✅ Create and login user in a single step
        $user = User::create($validated);
        Auth::login($user);

        // ✅ Regenerate session for security (session fixation protection)
        $request->session()->regenerate();

        // ✅ Redirect securely to intended route
        return redirect()->intended('/');
    }


    public function logout(Request $request)
    {
        Auth::logout(); // ✅ Log out the user

        $request->session()->invalidate(); // ✅ Invalidate session
        $request->session()->regenerateToken(); // ✅ Prevent CSRF attacks

        return redirect('/login'); // ✅ Redirect to login page
    }

}