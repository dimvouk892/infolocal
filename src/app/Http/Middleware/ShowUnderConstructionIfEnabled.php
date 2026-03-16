<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowUnderConstructionIfEnabled
{
    /**
     * When "Site Under Construction" is enabled, show the under-construction page
     * to non-admin visitors. Admins and auth routes (login, etc.) are always allowed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = filter_var(Setting::get('site_under_construction', 0), FILTER_VALIDATE_BOOLEAN);

        if (! $enabled) {
            return $next($request);
        }

        if ($request->user()?->isAdmin()) {
            return $next($request);
        }

        if ($request->is('admin/*') || $request->is('login') || $request->is('register')
            || $request->is('logout') || $request->is('forgot-password*') || $request->is('reset-password*')) {
            return $next($request);
        }

        return response()->view('under-construction');
    }
}
