@extends('layouts.app')

@section('title', __('messages.about.meta_title'))
@section('meta_description', __('messages.about.meta_description'))

@section('content')
    <section class="max-w-4xl mx-auto space-y-8 text-center">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-primary">
                {{ \App\Support\PageTextHelper::get('about', 'title', __('messages.cms.about_title')) }}
            </h1>
            <p class="mt-3 text-sm text-slate-600 max-w-2xl mx-auto">
                {{ \App\Support\PageTextHelper::get('about', 'intro', __('messages.cms.about_intro')) }}
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 text-left">
            <x-ui.card :hover="true">
                <h2 class="text-sm font-semibold text-primary mb-2">
                    {{ __('messages.cms.about_section1_title') }}
                </h2>
                <p class="text-xs text-slate-600">
                    {{ __('messages.cms.about_section1_body') }}
                </p>
            </x-ui.card>
            <x-ui.card :hover="true">
                <h2 class="text-sm font-semibold text-primary mb-2">
                    {{ __('messages.cms.about_section2_title') }}
                </h2>
                <p class="text-xs text-slate-600">
                    {{ __('messages.cms.about_section2_body') }}
                </p>
            </x-ui.card>
        </div>

        <x-ui.card :padding="true" class="bg-primary text-white border-primary text-left">
            <h2 class="text-sm font-semibold text-green-400">
                {{ __('messages.cms.about_section3_title') }}
            </h2>
            <p class="mt-2 text-xs text-white/90">
                {{ __('messages.cms.about_section3_body') }}
            </p>
        </x-ui.card>
    </section>
@endsection
