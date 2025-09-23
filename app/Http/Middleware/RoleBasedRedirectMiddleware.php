<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // If user is authenticated and trying to access the root login page
            if ($request->is('/') || $request->is('login') || $request->is('register')) {
                // Redirect based on user type
                if ($user instanceof \App\Models\Admin) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user instanceof \App\Models\Client) {
                    return redirect()->route('client.dashboard');
                }
            }
        }

        return $next($request);
    }
}
