<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeJwtCookieMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        dd('MIDDLEWARE IS RUNNING');

        return $next($request);
    }
}