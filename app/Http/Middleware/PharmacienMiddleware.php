<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacienMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'pharmacien') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Access denied. Only pharmacists can access this page.');
    }
}
