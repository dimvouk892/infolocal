<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('businesses')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $businesses = Business::orderBy('name')->get();
        return view('admin.users.create', compact('businesses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'is_active' => ['boolean'],
            'business_ids' => ['nullable', 'array'],
            'business_ids.*' => ['exists:businesses,id'],
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active');
        $businessIds = is_array($request->input('business_ids')) ? $request->input('business_ids') : [];
        unset($validated['business_ids']);
        $user = User::create($validated);
        if (! empty($businessIds)) {
            Business::whereIn('id', $businessIds)->update(['owner_id' => $user->id]);
        }
        return redirect()->route('admin.users.index')->with('success', __('User created.'));
    }

    public function edit(User $user): View
    {
        $businesses = Business::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'businesses'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'is_active' => ['boolean'],
            'business_ids' => ['nullable', 'array'],
            'business_ids.*' => ['exists:businesses,id'],
        ];

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['current_password']);
        unset($validated['business_ids']);
        $user->update($validated);
        Business::where('owner_id', $user->id)->update(['owner_id' => null]);
        $businessIds = $request->input('business_ids', []);
        if (! empty($businessIds)) {
            Business::whereIn('id', $businessIds)->update(['owner_id' => $user->id]);
        }
        return redirect()->route('admin.users.index')->with('success', __('User updated.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')->with('error', __('You cannot delete yourself.'));
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', __('User deleted.'));
    }
}
