<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = config('locales.available', ['en' => 'English', 'el' => 'Ελληνικά']);
        $locale = $request->session()->get('locale');

        if ($locale && isset($locales[$locale])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('locales.fallback', config('app.fallback_locale', 'en')));
        }

        return $next($request);
    }
}

