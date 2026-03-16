@extends('layouts.dashboard')

@section('title', __('messages.dashboard.my_businesses'))

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.dashboard.my_businesses') }}</h1>
    <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }} – business dashboard.</p>

    @if($businesses->isNotEmpty())
        <div class="mt-8 space-y-4">
            @if(session('error'))
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    {{ session('error') }}
                </div>
            @endif
            @foreach($businesses as $business)
                @php
                    $sub = $business->subscriptions->first();
                    $canManage = $sub && $sub->status === 'active' && $sub->end_date->toDateString() >= now()->toDateString();
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-medium text-slate-900">{{ $business->name }}</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ __('messages.dashboard.listing') }}: <span class="font-medium">{{ $business->status }}</span>
                            @if($sub)
                                · {{ __('messages.dashboard.renewal') }}: <span class="font-medium">{{ $sub->end_date->format('d/m/Y') }}</span>
                                @if($canManage)
                                    <span class="ml-1 inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">{{ __('messages.subscription.active') }}</span>
                                @elseif($sub->status === 'cancelled')
                                    <span class="ml-1 inline-flex rounded-full bg-slate-200 px-2 py-0.5 text-xs font-medium text-slate-700">{{ __('messages.subscription.cancelled') }}</span>
                                @else
                                    <span class="ml-1 inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">{{ __('messages.subscription.expired') }}</span>
                                @endif
                            @else
                                <span class="ml-1 inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ __('messages.dashboard.no_subscription') }}</span>
                            @endif
                        </p>
                    </div>
                    @if($canManage)
                        <a href="{{ route('dashboard.business.edit', $business) }}" class="inline-flex rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent-hover">
                            {{ __('messages.dashboard.edit_business') }}
                        </a>
                    @else
                        <span class="inline-flex rounded-lg px-4 py-2 text-sm font-medium {{ $sub && $sub->status === 'cancelled' ? 'border border-slate-300 bg-slate-100 text-slate-700' : 'border border-amber-300 bg-amber-50 text-amber-800' }}" title="{{ $sub && $sub->status === 'cancelled' ? __('messages.subscription.subscription_cancelled_contact_admin') : __('messages.subscription.renew_to_manage') }}">
                            {{ $sub && $sub->status === 'cancelled' ? __('messages.subscription.cancelled_renew') : __('messages.subscription.expired_renew') }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-8 rounded-xl bg-amber-50 border border-amber-200 p-6">
            <p class="text-sm text-amber-800">{{ __('messages.subscription.no_business_assigned') }}</p>
            <p class="mt-2 text-sm text-amber-700">Contact the administrator to have a business listing assigned to your account.</p>
        </div>
    @endif
@endsection
