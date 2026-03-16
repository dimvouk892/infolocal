@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Add User</h1>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
            <select id="role" name="role" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Assign businesses</label>
            <p class="mt-0.5 text-xs text-slate-500 mb-2">You can assign one or more businesses to this user.</p>
            <div class="space-y-2 max-h-48 overflow-y-auto rounded-lg border border-slate-300 p-3 bg-slate-50">
                @foreach($businesses as $b)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="business_ids[]" value="{{ $b->id }}" {{ in_array($b->id, old('business_ids', [])) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                        <span class="text-sm text-slate-700">{{ $b->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('business_ids')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                <span class="ml-2 text-sm text-slate-700">Active</span>
            </label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
