@extends('layouts.app')

@section('title', __('messages.auth.login') . ' – ' . __('messages.brand.name'))
@section('meta_description', __('messages.meta.description_default'))

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.auth.login') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }}</p>

        @if (session('status'))
            <p class="mt-4 text-sm text-emerald-600">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-4 text-sm text-red-600">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="ml-2 text-sm text-slate-600">{{ __('messages.auth.remember') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 hover:text-emerald-700">{{ __('messages.auth.forgot') }}</a>
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('messages.auth.login') }}
            </button>
        </form>
    </div>
@endsection
