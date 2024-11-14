<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastSessionActivityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $request->user()
                ->userSessions()
                ->where('session_id', $request->session()->getId())
                ->where('user_agent', $request->userAgent())
                ->update(['last_activity' => now()]);
        }

        return $next($request);
    }
}
