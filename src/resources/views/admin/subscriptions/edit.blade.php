@extends('layouts.admin')

@section('title', 'Edit Subscription')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Edit Subscription</h1>
    <p class="mt-1 text-sm text-slate-500">{{ $subscription->business?->name }} – {{ $subscription->plan?->name ?? '—' }}</p>

    @if($subscription->business)
        @php $owner = $subscription->business->owner; @endphp
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <h2 class="text-sm font-semibold text-slate-700">{{ __('Linked user') }}</h2>
            @if($owner)
                <p class="mt-1 text-sm text-slate-600">
                    <a href="{{ route('admin.users.edit', $owner) }}" class="font-medium text-emerald-600 hover:text-emerald-700">{{ $owner->name }}</a>
                    <span class="text-slate-500"> – {{ $owner->email }}</span>
                </p>
                <a href="{{ route('admin.users.edit', $owner) }}" class="mt-2 inline-block text-sm font-medium text-emerald-600 hover:text-emerald-700">{{ __('Edit user') }}</a>
            @else
                <p class="mt-1 text-sm text-slate-500">{{ __('No user assigned to this business.') }}</p>
            @endif
        </div>
    @endif

    <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}" class="mt-6 max-w-md space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="subscription_plan_id" class="block text-sm font-medium text-slate-700">Plan</label>
            <select id="subscription_plan_id" name="subscription_plan_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                @php $planId = old('subscription_plan_id'); @endphp
                <option value="" data-duration-days="0" {{ ($planId === null || $planId === '') ? 'selected' : '' }}>—</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" data-duration-days="{{ (int) ($plan->duration_days ?? 0) }}" {{ $planId == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
            <p class="mt-0.5 text-xs text-slate-500">Optional. If you change plan, dates are filled automatically (you can edit them afterwards).</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700">Start date</label>
                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700">End date</label>
                <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="active" {{ old('status', $subscription->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ old('status', $subscription->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ old('status', $subscription->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Update</button>
            <a href="{{ route('admin.subscriptions.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>

    <script>
    document.getElementById('subscription_plan_id').addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        var days = parseInt(opt.getAttribute('data-duration-days') || '0', 10);
        if (days <= 0) return;
        var start = document.getElementById('start_date');
        var end = document.getElementById('end_date');
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        start.value = today.toISOString().slice(0, 10);
        var endDate = new Date(today);
        endDate.setDate(endDate.getDate() + days);
        end.value = endDate.toISOString().slice(0, 10);
    });
    </script>
@endsection
