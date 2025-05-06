<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJWTBlacklist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = JWTAuth::getToken();

        $isRevoked = DB::table('jwt_blacklist')
            ->where('token', $token)
            ->exists();

        if ($isRevoked) {
            return response()->json(['error' => 'Token revoked.'], 401);
        }

        return $next($request);
    }
}
