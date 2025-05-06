<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User registered.',
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch(\Throwable $th) {
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error_code' => 'REGISTRATION_USER_ERROR'
            ], 500);
        }
    }
    
    public function logout() 
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            // Converter timestamp Unix para formato datetime
            $expiresAt = Carbon::createFromTimestamp(JWTAuth::payload($token)->get('exp'))->toDateTimeString();
    
            DB::table('jwt_blacklist')->insert([
                'token'      => (string) $token,
                'expires_at' => $expiresAt,
                'created_at' => now(),
            ]);
    
            return response()->json([
                'message' => 'Logout successful. Token invalidated.',
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to logout. Error: ' . $th->getMessage(),
            ], 500);
        }
    }
}
