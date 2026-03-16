@extends('layouts.app')

@section('title', __('messages.auth.reset') . ' – ' . __('messages.brand.name'))
@section('meta_description', __('messages.meta.description_default'))

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.auth.reset') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }}</p>

        @if ($errors->any())
            <ul class="mt-4 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ $email }}" required autofocus
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.password') }}</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.password') }} ({{ __('Confirm') }})</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('messages.auth.reset') }}
            </button>
        </form>
    </div>
@endsection
