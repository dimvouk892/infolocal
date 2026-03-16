@extends('layouts.admin')

@section('title', 'Business Categories')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Business Categories</h1>
    <p class="mt-1 text-sm text-slate-600">Business categories for the directory. Changes here sync with the public site.</p>

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-slate-800">Business categories</h2>
            <a href="{{ route('admin.business-categories.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add business category</a>
        </div>
        <div class="mt-3 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
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
                    @forelse($businessCategories as $category)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $category->slug }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $category->sort_order }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('admin.business-categories.edit', $category) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                                <form method="POST" action="{{ route('admin.business-categories.destroy', $category) }}" class="inline ml-2" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-sm text-slate-500 text-center">No business categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
