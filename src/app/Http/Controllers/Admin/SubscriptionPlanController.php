<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::orderBy('name')->get();
        return view('admin.subscriptions.plans-index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.subscriptions.plans-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        SubscriptionPlan::create($validated);
        return redirect()->route('admin.subscription-plans.index')->with('success', __('Plan created.'));
    }

    public function edit(SubscriptionPlan $subscription_plan): View
    {
        return view('admin.subscriptions.plans-edit', ['plan' => $subscription_plan]);
    }

    public function update(Request $request, SubscriptionPlan $subscription_plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $subscription_plan->update($validated);
        return redirect()->route('admin.subscription-plans.index')->with('success', __('Plan updated.'));
    }

    public function destroy(SubscriptionPlan $subscription_plan): RedirectResponse
    {
        $subscription_plan->delete();
        return redirect()->route('admin.subscription-plans.index')->with('success', __('Plan deleted.'));
    }
}
