<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareRoutePrefix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routePrefix = 'admin.'; // Default fallback

        if (Auth::check()) {
            $user = Auth::user();
            $roleName = ($user && $user->role) ? $user->role->name : null;
            $routePrefix = ($roleName === 'manager') ? 'manager.' : 'admin.';
            View::share('user', $user);
        }

        View::share('routePrefix', $routePrefix);

        return $next($request);
    }
}
