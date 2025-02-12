<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth('api')->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!auth('api')->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin role required.'], 403);
        }

        return $next($request);
    }
} 