<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminBypassVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu user là admin, tự động verify email
        if (auth()->check() && auth()->user()->role === 'admin') {
            $user = auth()->user();
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        }
        
        return $next($request);
    }
}
