<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            if ($user->isDriver()) {
                return redirect()->route('driver.dashboard')->with('warn', 'Anda tidak memiliki hak akses ke halaman Admin.');
            }

            return redirect()->route('dashboard')->with('warn', 'Halaman tersebut khusus untuk akun Driver.');
        }

        return $next($request);
    }
}
