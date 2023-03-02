<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        // $data = PersonalAccessToken::findToken($token)->tokenable();
        if ($user->role === 'admin') {
            $dataUser = auth('sanctum')->user();
        } else if ($user->role === 'guru') {
            $dataUser = $user->guru;
        } else {
            $dataUser = $user->siswa;
        }
        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => auth('sanctum')->user()->role,
            'user' => $dataUser
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }

    public function getUser()
    {
        $data = User::all();
        return response()->json([
            'data' => $data
        ]);
    }
}
