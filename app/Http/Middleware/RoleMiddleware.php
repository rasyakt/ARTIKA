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
        if (!$request->user() || !$request->user()->role) {
            abort(403, 'Unauthorized');
        }

        $userRole = strtolower(trim($request->user()->role->name));

        // Superadmin bypass: grant access to all role-protected routes
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        // Normalize roles to check against
        $roles = array_map(fn($role) => strtolower(trim($role)), $roles);

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
