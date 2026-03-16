@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Subscription Plans</h1>
        <a href="{{ route('admin.subscription-plans.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add plan</a>
    </div>
    <div class="mt-6 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Duration</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Active</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($plans as $plan)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $plan->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">€{{ number_format($plan->price, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $plan->duration ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $plan->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <form method="POST" action="{{ route('admin.subscription-plans.destroy', $plan) }}" class="inline ml-2" onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-sm text-slate-500 text-center">No plans yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="mt-4 text-sm text-slate-500"><a href="{{ route('admin.subscriptions.index') }}" class="text-emerald-600 hover:underline">Manage business subscriptions →</a></p>
@endsection
