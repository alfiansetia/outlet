<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->is_active) {
            Auth::guard('web')->logout();
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => 'Your account is not active. Please contact admin!'], 401);
            }
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not active. Please contact admin!.'])
                ->withInput();
        }
        return $next($request);
    }
}
