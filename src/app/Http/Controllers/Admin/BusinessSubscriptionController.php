<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessSubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = BusinessSubscription::with(['business', 'plan'])->latest()->paginate(20);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create(): View
    {
        $businesses = Business::orderBy('name')->get();
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('name')->get();
        return view('admin.subscriptions.create', compact('businesses', 'plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,expired,cancelled'],
        ]);
        if (($validated['subscription_plan_id'] ?? '') === '') {
            $validated['subscription_plan_id'] = null;
        }
        BusinessSubscription::create($validated);
        return redirect()->route('admin.subscriptions.index')->with('success', __('Subscription assigned.'));
    }

    public function edit(BusinessSubscription $subscription): View
    {
        $subscription->load('business.owner');
        $businesses = Business::orderBy('name')->get();
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('name')->get();
        // Include current plan in list if it was deactivated, so it stays selectable
        if ($subscription->plan && $plans->where('id', $subscription->subscription_plan_id)->isEmpty()) {
            $plans = $plans->push($subscription->plan)->sortBy('name')->values();
        }
        return view('admin.subscriptions.edit', compact('subscription', 'businesses', 'plans'));
    }

    public function update(Request $request, BusinessSubscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,expired,cancelled'],
        ]);
        if (array_key_exists('subscription_plan_id', $validated) && ($validated['subscription_plan_id'] ?? '') === '') {
            $validated['subscription_plan_id'] = null;
        }
        $subscription->update($validated);

        // When subscription becomes expired/cancelled, set business to pending so it no longer appears published
        if (in_array($validated['status'], ['expired', 'cancelled'], true)) {
            $business = $subscription->business;
            if ($business && $business->status === 'published' && ! $business->hasActiveSubscription()) {
                $business->update(['status' => 'pending']);
            }
        }

        return redirect()->route('admin.subscriptions.index')->with('success', __('Subscription updated.'));
    }

    public function destroy(BusinessSubscription $subscription): RedirectResponse
    {
        $business = $subscription->business;
        $subscription->delete();

        // If business has no active subscription and was published, set to pending
        if ($business && $business->status === 'published' && ! $business->hasActiveSubscription()) {
            $business->update(['status' => 'pending']);
        }

        return redirect()->route('admin.subscriptions.index')->with('success', __('Subscription removed.'));
    }
}
