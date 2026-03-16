<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        $businesses = $user->businesses;
        if ($businesses->isEmpty()) {
            return redirect()->route('dashboard.index')
                ->with('info', __('messages.subscription.no_business_assigned'));
        }

        if (! $businesses->contains(fn ($b) => $b->hasActiveSubscription())) {
            return redirect()->route('dashboard.subscription')
                ->with('error', __('messages.subscription.expired'));
        }

        return $next($request);
    }
}
