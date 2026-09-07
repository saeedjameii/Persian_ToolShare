<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $token = $request->cookie('token');

        if(!$token){
            return redirect()->route('login')->withCookie(cookie()->forget('token'))->withErrors(['email' => 'لطفا ابتدا وارد حساب خود شوید.']);
        }

        try{
            $user = JWTAuth::setToken($token)->authenticate();
        }
        catch(\Throwable $e){
            return redirect()->route('login')->withCookie(cookie()->forget('token'))->withErrors(['email' => 'لطفا ابتدا وارد حساب خود شوید.']);
        }

        if(!$user->hasPermission($permission)){
            abort(403);
        }

        return $next($request);
    }
}
