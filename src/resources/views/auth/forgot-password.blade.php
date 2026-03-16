@extends('layouts.app')

@section('title', __('messages.auth.forgot') . ' – ' . __('messages.brand.name'))
@section('meta_description', __('messages.meta.description_default'))

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-semibold text-slate-900">{{ __('messages.auth.forgot') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('messages.brand.name') }}</p>

        @if (session('status'))
            <p class="mt-4 text-sm text-emerald-600">{{ session('status') }}</p>
        @endif
        @if ($errors->has('email'))
            <p class="mt-4 text-sm text-red-600">{{ $errors->first('email') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700">{{ __('messages.auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('messages.auth.send_reset') }}
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-slate-600">
            <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-700">{{ __('messages.auth.login') }}</a>
        </p>
    </div>
@endsection
