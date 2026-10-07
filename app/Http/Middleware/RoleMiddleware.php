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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(auth()->user()->role?->nama_role ?? '');

        // If user is Admin, allow everything
        if ($userRole === 'admin') {
            return $next($request);
        }

        foreach ($roles as $role) {
            if (strtolower(trim($role)) === $userRole) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
