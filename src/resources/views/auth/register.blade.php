@extends('layouts.app')

@section('title', __('messages.auth.register') . ' – ' . __('messages.brand.name'))
@section('meta_description', __('messages.meta.description_default'))

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.auth.register') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }} – {{ __('messages.nav.businesses') }}</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">{{ __('messages.contact.fields.name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.password') }} ({{ __('messages.auth.confirm') }})</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('messages.auth.register') }}
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-slate-600">
            <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-700">{{ __('messages.auth.login') }}</a>
        </p>
    </div>
@endsection
