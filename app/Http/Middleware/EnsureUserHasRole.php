<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if ($permission && Gate::denies($permission)) {
            abort(403, 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        }

        // User is active by default (no is_active column in users table)

        // Check if user can access admin panel
        if (Gate::denies('access-admin')) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
