<?php

namespace Flobbos\LaravelSystemInfo\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = config('laravel-system-info.token');
        if ($request->bearerToken() !== $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
