<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        if (!$user->branch_id) {
            return response()->json([
                'message' => 'You Dont Have Branch, Contact Admin!'
            ], 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => new UserResource($user->load('branch')),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
