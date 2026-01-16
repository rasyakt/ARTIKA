<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check session first
        if (session()->has('language')) {
            App::setLocale(session('language'));
        }
        // Check query string
        elseif ($request->has('lang')) {
            $language = $request->query('lang');
            $supported = array_keys(config('app.supported_languages', ['id' => 'Indonesian', 'en' => 'English']));
            if (in_array($language, $supported)) {
                App::setLocale($language);
                session(['language' => $language]);
            }
        }

        return $next($request);
    }
}
