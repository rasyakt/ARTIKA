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
            return redirect()->route('admin.dashboard')->with('error', 'This feature is currently disabled by administrator.');
        }

        return $next($request);
    }
}
