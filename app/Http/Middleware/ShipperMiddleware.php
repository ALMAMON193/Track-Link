<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipperMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->user_type === 'shipper') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized. Shipper access only.'], 403);
    }
}
