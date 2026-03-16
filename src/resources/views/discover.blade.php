@extends('layouts.app')

@section('title', __('messages.cms.activities_title') . ' – ' . config('app.name', 'Local Guide'))
@section('meta_description', __('messages.cms.activities_intro'))

@section('content')
    <section class="max-w-4xl mx-auto space-y-12">
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl font-semibold text-primary">
                {{ __('messages.nav.destinations') }}
            </h1>
            <p class="mt-2 text-sm text-slate-600 max-w-2xl mx-auto">
                {{ __('messages.cms.about_intro') }}
            </p>
        </div>

        <x-ui.card :padding="true" class="border-slate-200/80">
            <h2 class="text-lg font-semibold text-primary mb-2">
                {{ optional($settings)->activities_title ?? __('messages.cms.activities_title') }}
            </h2>
            <p class="text-sm text-slate-600">
                {{ optional($settings)->activities_intro ?? __('messages.cms.activities_intro') }}
            </p>
            <p class="mt-3">
                <a href="{{ route('places.index') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.nav.places_to_visit') }} →
                </a>
            </p>
        </x-ui.card>

        <x-ui.card :padding="true" class="border-slate-200/80">
            <h2 class="text-lg font-semibold text-primary mb-2">
                {{ optional($settings)->beaches_title ?? __('messages.cms.beaches_title') }}
            </h2>
            <p class="text-sm text-slate-600">
                {{ optional($settings)->beaches_intro ?? __('messages.cms.beaches_intro') }}
            </p>
            <p class="mt-3">
                <a href="{{ route('places.index') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.nav.places_to_visit') }} →
                </a>
            </p>
        </x-ui.card>

        <x-ui.card :padding="true" class="border-slate-200/80">
            <h2 class="text-lg font-semibold text-primary mb-2">
                {{ optional($settings)->accommodation_title ?? __('messages.cms.accommodation_title') }}
            </h2>
            <p class="text-sm text-slate-600">
                {{ optional($settings)->accommodation_intro ?? __('messages.cms.accommodation_intro') }}
            </p>
            <p class="mt-3">
                <a href="{{ route('businesses') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.nav.businesses') }} →
                </a>
            </p>
        </x-ui.card>

        <x-ui.card :padding="true" class="border-slate-200/80">
            <h2 class="text-lg font-semibold text-primary mb-2">
                {{ optional($settings)->food_title ?? __('messages.cms.food_title') }}
            </h2>
            <p class="text-sm text-slate-600">
                {{ optional($settings)->food_intro ?? __('messages.cms.food_intro') }}
            </p>
            <p class="mt-3">
                <a href="{{ route('businesses') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.nav.businesses') }} →
                </a>
            </p>
        </x-ui.card>
    </section>
@endsection
