@extends('layouts.admin')

@section('title', 'Assign Subscription')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Assign Subscription</h1>
    <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="mt-6 max-w-md space-y-4">
        @csrf
        <div>
            <label for="business_id" class="block text-sm font-medium text-slate-700">Business</label>
            <select id="business_id" name="business_id" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="">—</option>
                @foreach($businesses as $b)
                    <option value="{{ $b->id }}" {{ old('business_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
            @error('business_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="subscription_plan_id" class="block text-sm font-medium text-slate-700">Plan</label>
            <select id="subscription_plan_id" name="subscription_plan_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="" data-duration-days="0">—</option>
                @foreach($plans as $p)
                    <option value="{{ $p->id }}" data-duration-days="{{ (int) ($p->duration_days ?? 0) }}" {{ old('subscription_plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} (€{{ number_format($p->price, 2) }})</option>
                @endforeach
            </select>
            <p class="mt-0.5 text-xs text-slate-500">Optional. If you select a plan, start/end dates are filled automatically (you can change them afterwards).</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700">Start date</label>
                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700">End date</label>
                <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ old('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Assign</button>
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
