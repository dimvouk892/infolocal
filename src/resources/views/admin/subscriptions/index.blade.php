@extends('layouts.admin')

@section('title', 'Business Subscriptions')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Business Subscriptions</h1>
        <a href="{{ route('admin.subscriptions.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Assign subscription</a>
    </div>
    <p class="mt-2 text-sm text-slate-500"><a href="{{ route('admin.subscription-plans.index') }}" class="text-emerald-600 hover:underline">Manage plans →</a></p>
    <div class="mt-6 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Business</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Plan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Start</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">End</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($subscriptions as $sub)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $sub->business?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $sub->plan?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $sub->start_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $sub->end_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $sub->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $sub->status }}</span></td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.subscriptions.edit', $sub) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <form method="POST" action="{{ route('admin.subscriptions.destroy', $sub) }}" class="inline ml-2" onsubmit="return confirm('Remove this subscription?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No subscriptions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $subscriptions->links() }}</div>
@endsection
