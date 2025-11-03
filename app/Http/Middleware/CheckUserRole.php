<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Allow sign-proposal route to be accessed without authentication
        if ($request->routeIs('filament.admin.resources.proposals.sign-proposal') || 
            $request->path() === 'proposals/{record}/sign-proposal' ||
            str_contains($request->path(), 'sign-proposal')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('filament.auth.login');
        }

        if (Auth::user()->user_role !== $role) {
            // Redirect users to their appropriate panel
            if (Auth::user()->user_role === 'customer') {
                return redirect('/customer');
            }elseif (Auth::user()->user_role === 'tester') {
                return redirect('/tools');
            }elseif (Auth::user()->user_role === 'admin') {
                return redirect('/'); // Admin panel at root URL
            }
            
            // Fallback if role doesn't match any known panel
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}