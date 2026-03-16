@extends('layouts.admin')

@section('title', 'Add Subscription Plan')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Add Subscription Plan</h1>
    <form method="POST" action="{{ route('admin.subscription-plans.store') }}" class="mt-6 max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-slate-700">Price</label>
            <input id="price" type="number" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="duration" class="block text-sm font-medium text-slate-700">Duration (e.g. 1 year)</label>
                <input id="duration" type="text" name="duration" value="{{ old('duration') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
            <div>
                <label for="duration_days" class="block text-sm font-medium text-slate-700">Duration (days)</label>
                <input id="duration_days" type="number" name="duration_days" value="{{ old('duration_days') }}" min="0" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                <span class="ml-2 text-sm text-slate-700">Active</span>
            </label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.subscription-plans.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
