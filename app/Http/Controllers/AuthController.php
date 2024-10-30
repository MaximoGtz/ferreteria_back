<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);
        $user = User::create($fields);
        $token = $user->createToken($request->name);
        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required|exists:users',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'The provided credentials are incorrect'
            ];
        }
        $token = $user->createToken($user->name);
        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out successfully'
        ];
    }
}
// prueba