@extends('layouts.admin')

@section('title', 'Places to Visit')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Places to Visit</h1>
        <a href="{{ route('admin.places.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add place</a>
    </div>
    <p class="mt-1 text-sm text-slate-500">Manage museums, beaches, landmarks, parks, monuments and attractions. Only admins can create, edit or delete.</p>
    <div class="mt-6 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Image</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Featured</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($places as $place)
                    <tr>
                        <td class="px-4 py-3">
                            <x-image-or-placeholder :src="$place->featured_image" :alt="$place->title" class="h-12 w-16 rounded object-cover" />
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $place->title }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $place->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $place->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $place->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $place->featured ? 'Yes' : '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.places.edit', $place) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            <form method="POST" action="{{ route('admin.places.destroy', $place) }}" class="inline ml-2" onsubmit="return confirm('Delete this place?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No places yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
