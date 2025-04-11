<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUserById($id)
    {
        $user = DB::table('users')->select('id', 'name', 'email', 'point', 'created_at', 'updated_at')->find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        return response()->json($user, 200);
    }
}