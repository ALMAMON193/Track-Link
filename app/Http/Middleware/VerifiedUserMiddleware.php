<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (! $user->is_verified || $user->status !== 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not verified. Please verify your email to proceed.',
                ], 403);
            }
        }

        return $next($request);
    }
}
