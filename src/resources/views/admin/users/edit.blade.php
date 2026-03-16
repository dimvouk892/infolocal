@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 max-w-md space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
            <p class="mt-0.5 text-xs text-slate-500">Leave blank to keep current password. Only fill if you want to change it.</p>
            <div class="mt-1 relative">
                <input id="password" type="password" name="password" autocomplete="new-password" class="block w-full rounded-lg border-slate-300 shadow-sm text-sm pr-10">
                <button type="button" id="toggle-password" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm new password</label>
            <div class="mt-1 relative">
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="block w-full rounded-lg border-slate-300 shadow-sm text-sm pr-10">
                <button type="button" id="toggle-password-confirm" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg id="icon-eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="icon-eye-off-confirm" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
            <select id="role" name="role" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Assign businesses</label>
            <p class="mt-0.5 text-xs text-slate-500 mb-2">You can assign one or more businesses to this user. Select at least one for business users.</p>
            <div class="space-y-2 max-h-48 overflow-y-auto rounded-lg border border-slate-300 p-3 bg-slate-50">
                @foreach($businesses as $b)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="business_ids[]" value="{{ $b->id }}" {{ in_array($b->id, old('business_ids', $user->businesses->pluck('id')->all())) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                        <span class="text-sm text-slate-700">{{ $b->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('business_ids')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                <span class="ml-2 text-sm text-slate-700">Active</span>
            </label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Update</button>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>

    <script>
        (function () {
            function setupToggle(btnId, inputId, iconOnId, iconOffId) {
                var btn = document.getElementById(btnId);
                var input = document.getElementById(inputId);
                var iconOn = document.getElementById(iconOnId);
                var iconOff = document.getElementById(iconOffId);
                if (!btn || !input) return;
                btn.addEventListener('click', function () {
                    var isPass = input.type === 'password';
                    input.type = isPass ? 'text' : 'password';
                    if (iconOn) iconOn.classList.toggle('hidden', isPass);
                    if (iconOff) iconOff.classList.toggle('hidden', !isPass);
                });
            }
            setupToggle('toggle-password', 'password', 'icon-eye', 'icon-eye-off');
            setupToggle('toggle-password-confirm', 'password_confirmation', 'icon-eye-confirm', 'icon-eye-off-confirm');
        })();
    </script>
@endsection
