<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate tokens
        $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(60));
        
        // Refresh Token: 7 days
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7));

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => 60 * 60, // seconds
            'user' => $user
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        if (!$user->currentAccessToken()->can('issue-access-token')) {
            return response()->json(['message' => 'Invalid token type. Refresh token required.'], 403);
        }

        $user->currentAccessToken()->delete();
        $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(60));
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7));

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => 60 * 60
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
