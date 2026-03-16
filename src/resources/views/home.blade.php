@extends('layouts.app')

@section('title', app()->getLocale() === 'el' ? __('messages.home.meta_title') : (optional($settings)->meta_title ?: __('messages.home.meta_title')))
@section('meta_description', app()->getLocale() === 'el' ? __('messages.home.meta_description') : (optional($settings)->meta_description ?: __('messages.home.meta_description')))

@section('hero')
@php
    $heroImagesRaw = optional($settings)->hero_images ?? [];
    $heroImagesRaw = is_array($heroImagesRaw) ? $heroImagesRaw : [];
    $heroImages = array_map(function ($url) {
        $url = trim($url);
        if ($url === '') return null;
        return (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) ? $url : asset('storage/' . ltrim($url, '/'));
    }, $heroImagesRaw);
    $heroImages = array_values(array_filter($heroImages));
    $singleHero = optional($settings)->hero_image ?? 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg';
    $singleHero = (str_starts_with($singleHero, 'http://') || str_starts_with($singleHero, 'https://')) ? $singleHero : asset('storage/' . ltrim($singleHero, '/'));
    $heroOverlay = optional($settings)->hero_overlay;
    $heroOverlay = (is_numeric($heroOverlay) && $heroOverlay >= 0 && $heroOverlay <= 1) ? (float) $heroOverlay : 0.9;
    $heroOverlayColor = optional($settings)->hero_overlay_color;
    $heroOverlayColor = (is_string($heroOverlayColor) && preg_match('/^#?[0-9A-Fa-f]{6}$/', trim($heroOverlayColor))) ? (str_starts_with(trim($heroOverlayColor), '#') ? trim($heroOverlayColor) : '#' . trim($heroOverlayColor)) : (optional($settings)->user_primary_color ?? optional($settings)->primary_color ?? '#1E3A5F');
    $heroIntervalSec = optional($settings)->hero_slideshow_interval;
    $heroIntervalSec = (is_numeric($heroIntervalSec) && $heroIntervalSec >= 2 && $heroIntervalSec <= 60) ? (int) $heroIntervalSec : 5;
    $heroIntervalMs = $heroIntervalSec * 1000;
    $heroTransitionSec = optional($settings)->hero_slideshow_transition;
    $heroTransitionSec = (is_numeric($heroTransitionSec) && $heroTransitionSec >= 0.5 && $heroTransitionSec <= 5) ? (float) $heroTransitionSec : 1.5;
@endphp
<section class="relative overflow-hidden bg-primary" @if(count($heroImages) > 1) x-data="{ heroSlide: 0, prevSlide: 0, total: {{ count($heroImages) }} }" x-init="setInterval(() => { prevSlide = heroSlide; heroSlide = (heroSlide + 1) % total }, {{ $heroIntervalMs }})" @endif>
    <div class="absolute inset-0">
        @if(count($heroImages) > 1)
            @foreach($heroImages as $i => $src)
                <img src="{{ $src }}"
                     alt=""
                     class="absolute inset-0 h-full w-full object-cover object-center transition-opacity ease-in-out"
                     style="transition-duration: {{ $heroTransitionSec }}s"
                     :class="{
                         'opacity-100 z-10': prevSlide === {{ $i }} && heroSlide === {{ $i }},
                         'opacity-0 z-10': prevSlide === {{ $i }} && heroSlide !== {{ $i }},
                         'opacity-100 z-0': heroSlide === {{ $i }} && prevSlide !== {{ $i }},
                         'opacity-0 z-[-1]': heroSlide !== {{ $i }} && prevSlide !== {{ $i }}
                     }">
            @endforeach
        @else
            <img src="{{ count($heroImages) === 1 ? $heroImages[0] : $singleHero }}"
                 alt=""
                 class="h-full w-full object-cover object-center">
        @endif
        <div class="absolute inset-0 z-[20] pointer-events-none" style="background-color: {{ $heroOverlayColor }}; opacity: {{ $heroOverlay }}; transform: translateZ(0); backface-visibility: hidden;"></div>
    </div>

    <div class="relative z-[30] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
        <div class="max-w-2xl space-y-6">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white break-words drop-shadow-md">
                {{ \App\Support\PageTextHelper::get('home', 'hero_title', __('messages.home.hero_title')) }}
            </h1>
            <p class="text-base sm:text-lg text-white max-w-xl drop-shadow-sm">
                {{ \App\Support\PageTextHelper::get('home', 'hero_subtitle', __('messages.home.hero_subtitle')) }}
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <x-ui.button href="{{ route('businesses') }}" variant="white" size="lg" class="shadow-lg">
                    {{ __('messages.nav.businesses') }}
                </x-ui.button>
                <x-ui.button href="{{ route('places.index') }}" variant="ghost" size="lg" class="!text-white !border-white/80 hover:!bg-white/20 shadow-md">
                    {{ __('messages.nav.places_to_visit') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
    <section class="space-y-6">
        <x-ui.section-header
            :title="\App\Support\PageTextHelper::get('home', 'promoted_businesses_title', __('messages.home.promoted_businesses_title'))"
            :subtitle="\App\Support\PageTextHelper::get('home', 'promoted_businesses_subtitle', __('messages.home.promoted_businesses_subtitle'))"
        >
            <x-slot:action>
                <a href="{{ route('businesses') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.buttons.view_all_businesses') }}
                </a>
            </x-slot:action>
        </x-ui.section-header>
        <div class="mt-4 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredBusinesses as $business)
                <x-business-card :business="$business" />
            @endforeach
        </div>
    </section>

    {{-- Places to Visit --}}
    @if($featuredPlaces->isNotEmpty())
    <section class="mt-16 sm:mt-20">
        <div class="rounded-2xl bg-secondary/60 border border-amber-200/50 p-6 sm:p-8 shadow-sm">
        <x-ui.section-header
            :title="__('messages.cms.featured_places_title')"
            :subtitle="__('messages.cms.featured_places_subtitle')"
        >
            <x-slot:action>
                <a href="{{ route('places.index') }}" class="text-sm font-semibold text-accent hover:text-accent-hover">
                    {{ __('messages.buttons.view_all_destinations') }}
                </a>
            </x-slot:action>
        </x-ui.section-header>
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredPlaces as $place)
                <x-place-card :place="$place" />
            @endforeach
        </div>
        </div>
    </section>
    @endif

    <section class="mt-16 sm:mt-20">
        <x-ui.card :padding="true" :hover="false" class="bg-secondary/50 border-amber-200/40 shadow-sm">
            <div class="grid gap-8 md:grid-cols-2 items-center min-w-0">
                <div>
                    <h2 class="text-2xl font-semibold text-primary">
                        {{ \App\Support\PageTextHelper::get('home', 'why_title', __('messages.home.why_title')) }}
                    </h2>
                    <p class="mt-3 text-sm text-slate-600">
                        {{ \App\Support\PageTextHelper::get('home', 'why_intro', __('messages.home.why_intro')) }}
                    </p>
                    <dl class="mt-6 space-y-4">
                        @foreach([1, 2, 3] as $i)
                        <div class="flex space-x-3">
                            <dt class="mt-1">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-secondary text-accent text-sm font-semibold">{{ $i }}</span>
                            </dt>
                            <dd>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ __('messages.home.why_point' . $i . '_title') }}
                                </p>
                                <p class="text-xs text-slate-600 mt-1">
                                    {{ __('messages.home.why_point' . $i . '_body') }}
                                </p>
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                <div class="space-y-4">
                    <div class="rounded-2xl bg-primary p-6 text-white shadow-xl">
                        <p class="text-sm uppercase tracking-wide font-semibold" style="color: var(--color-accent)">
                            {{ __('messages.home.testimonials_badge') }}
                        </p>
                        <p class="mt-3 text-base text-white/95">
                            {{ __('messages.home.testimonials_quote') }}
                        </p>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </section>

    <section class="mt-16 sm:mt-20">
        <div class="relative overflow-hidden rounded-3xl bg-primary text-white px-6 py-12 sm:px-10 sm:py-14 shadow-xl">
            <div class="absolute inset-0 opacity-15">
                <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-amber-400 blur-3xl"></div>
                <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-amber-300 blur-3xl"></div>
            </div>
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold">
                        {{ __('messages.home.cta_title') }}
                    </h2>
                    <p class="mt-2 text-sm text-white/80 max-w-md">
                        {{ __('messages.home.cta_subtitle') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button href="{{ route('contact') }}" variant="white">
                        {{ __('messages.home.cta_primary') }}
                    </x-ui.button>
                    <x-ui.button href="{{ route('businesses') }}" variant="ghost" class="!text-white hover:!bg-white/10 !border-white/40">
                        {{ __('messages.home.cta_secondary') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>

@endsection
