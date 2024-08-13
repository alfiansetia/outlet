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
        $user = auth()->user();
        if (!$user || !$user->is_active || !$user->branch_id) {
            $message = 'Your account is not active. Please contact admin!';
            Auth::guard('web')->logout();
            if (!$user->branch_id) {
                $message = 'You Dont Have Branch, Contact Admin!';
            }
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['message' => $message], 401);
            }
            return redirect()->route('login')
                ->withErrors(['email' => $message])
                ->withInput();
        }
        return $next($request);
    }
}
