<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TruckerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->user_type === 'trucker') {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized. Trucker access only.'], 403);
    }
}
