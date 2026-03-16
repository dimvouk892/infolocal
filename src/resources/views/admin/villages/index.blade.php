@extends('layouts.admin')

@section('title', 'Villages')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Villages</h1>
        <a href="{{ route('admin.villages.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add village</a>
    </div>
    <p class="mt-1 text-sm text-slate-500">Villages are linked to businesses and places for filtering.</p>
    <div class="mt-6 rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Sort</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($villages as $village)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $village->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $village->slug }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $village->sort_order }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.villages.edit', $village) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <form method="POST" action="{{ route('admin.villages.destroy', $village) }}" class="inline ml-2" onsubmit="return confirm('Delete?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No villages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
