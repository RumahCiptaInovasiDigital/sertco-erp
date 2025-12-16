<?php

namespace App\Http\Middleware\API;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMobileMIddlewareMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'user' => $user
            ], 401);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
