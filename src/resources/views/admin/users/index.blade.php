@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Users</h1>
        <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add user</a>
    </div>
    <div class="mt-6 rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Businesses</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Active</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-600' }}">{{ $user->role }}</span></td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $user->businesses->isEmpty() ? '—' : $user->businesses->pluck('name')->join(', ') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $user->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline ml-2" onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-sm text-slate-500 text-center">No users yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
