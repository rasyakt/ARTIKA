<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class CheckFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $feature
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $feature)
    {
        if (!Setting::get($feature, true)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This feature is currently disabled.'], 403);
            }

            // Redirect to the appropriate dashboard based on user role
            $redirectRoute = 'dashboard';
            if ($request->user()) {
                $role = strtolower($request->user()->role->name ?? '');
                $redirectRoute = match ($role) {
                    'superadmin', 'admin' => 'admin.dashboard',
                    'manager' => 'manager.dashboard',
                    'cashier' => 'pos.index',
                    'warehouse' => 'warehouse.dashboard',
                    default => 'dashboard',
                };
            }

            return redirect()->route($redirectRoute)->with('error', 'This feature is currently disabled by administrator.');
        }

        return $next($request);
    }
}
