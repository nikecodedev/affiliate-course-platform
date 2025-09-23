<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
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
        if (!Auth::guard('client')->check()) {
            return redirect()->route('client.login')
                ->with('error', 'Você precisa fazer login para acessar esta área.');
        }

        $client = Auth::guard('client')->user();

        // Check if client account is active
        if (!$client->is_active) {
            Auth::guard('client')->logout();
            return redirect()->route('client.login')
                ->with('error', 'Sua conta foi desativada. Entre em contato com o suporte.');
        }

        // Check if client account is locked
        if ($client->isLocked()) {
            Auth::guard('client')->logout();
            return redirect()->route('client.login')
                ->with('error', 'Sua conta está temporariamente bloqueada. Tente novamente mais tarde.');
        }

        return $next($request);
    }
}
