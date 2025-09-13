<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePharmacienHasLicence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->role === 'pharmacien') {
            $pharmacien = \App\Models\Pharmacien::where('user_id', $user->id)->first();
            if (!$pharmacien || empty($pharmacien->licence)) {
                // Prevent infinite redirect loop
                if (!$request->is('licence-form')) {
                    return redirect()->route('licence.form');
                }
            }
        }
        return $next($request);
    }
}
