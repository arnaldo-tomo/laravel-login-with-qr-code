<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForSessionValidityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $session = $request->user()->userSessions()
                ->where('session_id', $request->session()->getId())->first();

            if (!$session) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login');
            }
        }
        return $next($request);
    }
}
