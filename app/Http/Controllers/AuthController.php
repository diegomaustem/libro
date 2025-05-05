<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request) 
    {
        $credentials = $request->validated();

        try {
            $user = User::where('email', strtolower($credentials['email']))->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials.',
                    'error' => 'AUTH_CREDENTIALS_INVALID'
                ], 401);
            }

            $token = JWTAuth::fromUser($user); 

            return response()->json([
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error logging in. Please try again.',
                'error_code' => 'ERROR_LOGIN',
            ], 500);
        }
    }

    public function register(StoreRegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken(
                name: 'auth_token',
                abilities: ['*'],
                expiresAt: now()->addHours(8)
            );

            return response()->json([
                'message' => 'User registered.',
                'user' => $user,
                'token' => $token->plainTextToken,
            ], 201);

        } catch(\Throwable $th) {
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error_code' => 'REGISTRATION_USER_ERROR'
            ], 500);
        }
    }
}
