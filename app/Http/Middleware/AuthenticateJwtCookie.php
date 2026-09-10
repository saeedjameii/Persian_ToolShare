<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateJwtCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');

        if ($token) {
            try {
                JWTAuth::setToken($token)->authenticate();
            } catch (\Throwable $e) {
                // توکن نامعتبر/منقضی: صرفاً کاربر را ناشناس در نظر می‌گیریم
            }
        }

        return $next($request);
    }
}