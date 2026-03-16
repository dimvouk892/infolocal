@extends('layouts.dashboard')

@section('title', __('messages.subscription.status'))

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.subscription.status') }}</h1>
    <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }} – {{ __('messages.subscription.subscription_renewal_per_business') }}</p>

    @if($businesses->isNotEmpty())
        <div class="mt-8 space-y-6">
            @foreach($businesses as $business)
                @php
                    $sub = $business->subscriptions->first();
                    $active = $business->hasActiveSubscription();
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $business->name }}</h2>
                    @if($sub)
                        <dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                            <div>
                                <span class="text-slate-500">{{ __('messages.subscription.plan') }}:</span>
                                <span class="font-medium text-slate-700">{{ $sub->plan->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500">{{ __('messages.subscription.start') }}:</span>
                                <span class="text-slate-700">{{ $sub->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500">{{ __('messages.subscription.renewal_end_date') }}:</span>
                                <span class="text-slate-700">{{ $sub->end_date->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500">{{ __('messages.subscription.status') }}:</span>
                                @if($active)
                                    <span class="font-medium text-emerald-600">{{ __('messages.subscription.active') }}</span>
                                @elseif($sub->status === 'cancelled')
                                    <span class="font-medium text-slate-600">{{ __('messages.subscription.cancelled') }}</span>
                                @else
                                    <span class="font-medium text-amber-600">{{ __('messages.subscription.expired') }}</span>
                                @endif
                            </div>
                        </dl>
                        @if(!$active)
                            <p class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">
                                @if($sub->status === 'cancelled')
                                    {{ __('messages.subscription.cancelled') }}. {{ __('messages.subscription.contact_admin_renew') }}
                                @else
                                    {{ __('messages.subscription.expired') }} {{ __('messages.subscription.contact_admin_renew') }}
                                @endif
                            </p>
                        @endif
                    @else
                        <p class="mt-4 text-sm text-slate-600">{{ __('messages.subscription.no_subscription_recorded') }}</p>
                        <p class="mt-2 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">
                            {{ __('messages.subscription.contact_admin_activate') }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-8 rounded-xl border border-slate-200 bg-slate-50 p-6">
            <p class="text-sm text-slate-600">{{ __('messages.subscription.no_business_assigned') }}</p>
        </div>
    @endif
@endsection
