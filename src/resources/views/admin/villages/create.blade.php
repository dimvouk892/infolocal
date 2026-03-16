@extends('layouts.admin')

@section('title', 'Add Village')

@section('content')
    <div class="space-y-2">
        <h1 class="text-2xl font-semibold text-slate-900">Add Village</h1>
        <p class="text-sm text-slate-500">Villages can be linked to businesses and places.</p>
    </div>
    <form method="POST" action="{{ route('admin.villages.store') }}" class="mt-6 max-w-2xl space-y-6">
        @csrf
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name (EN)</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name_el" class="block text-sm font-medium text-slate-700">Name (ΕΛ)</label>
                    <input id="name_el" type="text" name="name_el" value="{{ old('name_el') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Μαργαρίτες">
                    @error('name_el')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 mt-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.villages.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
