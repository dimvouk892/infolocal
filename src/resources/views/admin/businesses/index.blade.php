@extends('layouts.admin')

@section('title', 'Businesses')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Businesses</h1>
        <a href="{{ route('admin.businesses.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add business</a>
    </div>
    <div class="mt-6 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Logo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Owner</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($businesses as $business)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex h-12 w-16 items-center justify-center rounded border border-slate-100 bg-slate-50">
                                <x-image-or-placeholder :src="$business->logo" alt="{{ $business->name }}" class="max-h-12 max-w-full w-auto object-contain" />
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $business->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $business->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $business->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $business->status }}</span></td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $business->owner?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.businesses.edit', $business) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <form method="POST" action="{{ route('admin.businesses.destroy', $business) }}" class="inline ml-2" onsubmit="return confirm('Delete this business?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No businesses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
