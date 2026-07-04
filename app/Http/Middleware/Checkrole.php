<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Checkrole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->roles == "superadmin") {
            return $next($request);
        } elseif (Auth::user()->roles == "admin") {
            return $next($request);
        } elseif (Auth::user()->roles == "pimpinan") {
            return $next($request);
        }
        return $next($request);
    }
}
