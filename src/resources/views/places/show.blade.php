@extends('layouts.app')

@php
    $placeTitle = $place->title ?? '';
    $placeCategory = $place->category?->name ?? '';
    $placeDescription = $place->full_content ?? $place->short_description ?? '';
    $placeFeaturedImage = $place->featured_image ?? '';
    $placeGallery = is_array($place->gallery ?? null) ? $place->gallery : [];
    $placeFeatured = $place->featured ?? false;
    $placeAddress = $place->address ?? '';
    $placePhone = trim((string)($place->phone ?? ''));
    $placeEmail = trim((string)($place->email ?? ''));
    $placeWebsite = trim((string)($place->website ?? ''));
    $placeWebsiteDisplay = __('messages.places.visit_website');
    if ($placeWebsite && !preg_match('~^https?://~i', $placeWebsite)) {
        $placeWebsite = 'https://' . $placeWebsite;
    }
    if ($placeWebsite) {
        $host = parse_url($placeWebsite, PHP_URL_HOST);
        if ($host) {
            $placeWebsiteDisplay = preg_replace('/^www\./i', '', $host);
        }
    }
    $videoEmbedUrl = $place->video_embed_url ?? null;
    $mapLoc = $place->map_location ?? null;
    $hasMap = is_array($mapLoc) && isset($mapLoc['lat'], $mapLoc['lng']);
@endphp

@section('title', $placeTitle . ' | ' . __('messages.places.meta_title_suffix'))
@section('meta_description', Str::limit($place->short_description ?? $placeDescription, 160))

@if($hasMap)
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
@endif

@if(!empty($placeGallery))
    @push('head')
        <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    @endpush
@endif

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <article class="mx-auto max-w-6xl min-w-0 space-y-6 sm:space-y-8">

        {{-- Hero --}}
        <section class="relative overflow-hidden rounded-[2rem] shadow-2xl">
            <div class="absolute inset-0">
                @if($placeFeaturedImage)
                    <img src="{{ str_starts_with($placeFeaturedImage, 'http') ? $placeFeaturedImage : asset('storage/' . ltrim($placeFeaturedImage, '/')) }}"
                         alt="{{ $placeTitle }}"
                         class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-primary"></div>
                @endif
                <div class="absolute inset-0 bg-primary/50"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/50 to-primary/20"></div>
            </div>
            <div class="relative px-5 py-10 sm:px-8 sm:py-14 lg:px-10 lg:py-16 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-2xl min-w-0">
                    @if($placeCategory)
                        <div class="mb-4 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-white backdrop-blur">
                                {{ $placeCategory }}
                            </span>
                        </div>
                    @endif
                    <h1 class="break-words text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-5xl drop-shadow-sm">
                        {{ $placeTitle }}
                    </h1>
                    @if($place->short_description)
                        <p class="mt-4 max-w-xl text-sm leading-6 text-slate-200 sm:text-base">
                            {{ Str::limit($place->short_description, 200) }}
                        </p>
                    @endif
                </div>
                @if($placePhone || $placeWebsite)
                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        @if($placePhone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $placePhone) }}"
                               class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-phone text-accent-600"></i>
                                {{ __('messages.buttons.call_now') }}
                            </a>
                        @endif
                        @if($placeWebsite)
                            <a href="{{ $placeWebsite }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur hover:bg-white/20 transition">
                                <i class="fa-solid fa-up-right-from-square text-white"></i>
                                {{ $placeWebsiteDisplay }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        <section class="max-w-6xl mx-auto space-y-6 sm:space-y-8 min-w-0">
            {{-- About / description --}}
                <div class="rounded-[2rem] bg-white p-5 sm:p-7 lg:p-8 shadow-xl border border-slate-200 overflow-hidden">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900 mb-5">
                        <i class="fa-solid fa-circle-info text-accent-600"></i>
                        {{ __('messages.places.about_title') }}
                    </h2>
                    @if($placeDescription)
                        <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e($placeDescription)) !!}
                        </div>
                    @else
                        <p class="text-slate-500 italic">{{ __('messages.places.about_placeholder') }}</p>
                    @endif
            </div>

            {{-- Video --}}
                @if($videoEmbedUrl)
                    <div class="rounded-[2rem] bg-white p-5 sm:p-7 lg:p-8 shadow-xl border border-slate-200 overflow-hidden">
                        <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900 mb-5">
                            <i class="fa-solid fa-video text-accent-600"></i>
                            {{ __('messages.places.video_title') }}
                        </h2>
                        <div class="aspect-video rounded-2xl overflow-hidden bg-slate-100 shadow-inner ring-1 ring-slate-200">
                            <iframe
                                src="{{ $videoEmbedUrl }}?rel=0"
                                class="h-full w-full"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                title="{{ $placeTitle }}"
                            ></iframe>
                        </div>
                    </div>
                @endif

            {{-- Gallery --}}
                <div class="rounded-[2rem] bg-white p-5 sm:p-7 lg:p-8 shadow-xl border border-slate-200 overflow-hidden">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900 mb-5">
                        <i class="fa-solid fa-images text-accent-600"></i>
                        {{ __('messages.places.gallery_title') }}
                    </h2>
                    @if(!empty($placeGallery))
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach($placeGallery as $path)
                                @php
                                    $fullUrl = str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'));
                                @endphp
                                <a
                                    href="{{ $fullUrl }}"
                                    class="group relative aspect-square overflow-hidden rounded-2xl ring-1 ring-slate-200/50"
                                    data-glightbox="title: {{ e($placeTitle) }}; description: {{ e(Str::limit($placeDescription, 80)) }}"
                                >
                                    <x-image-or-placeholder
                                        :src="$path"
                                        :alt="$placeTitle"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="rounded-full bg-white/90 p-2 text-slate-700">
                                            <i class="fa-solid fa-expand text-sm"></i>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if (typeof GLightbox !== 'undefined') {
                                    GLightbox({ selector: '[data-glightbox]' });
                                }
                            });
                        </script>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 rounded-2xl bg-secondary/30 border border-dashed border-slate-200">
                            <i class="fa-solid fa-images text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">{{ __('messages.places.gallery_placeholder') }}</p>
                        </div>
                    @endif
                </div>

            {{-- Map & contact info --}}
                <div class="rounded-[2rem] bg-white p-5 sm:p-7 lg:p-8 shadow-xl border border-slate-200 overflow-hidden">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900 mb-4">
                        <i class="fa-solid fa-location-dot text-accent-600"></i>
                        {{ __('messages.places.location_title') }}
                    </h2>
                    @if($placeAddress || $placePhone || $placeEmail || $placeWebsite)
                        <div class="flex flex-wrap gap-4 mb-5 text-sm">
                            @if($placeAddress)
                                <span class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-location-dot text-accent-600"></i>
                                    {{ $placeAddress }}
                                </span>
                            @endif
                            @if($placePhone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $placePhone) }}" class="flex items-center gap-2 text-accent-600 hover:underline">
                                    <i class="fa-solid fa-phone text-accent-600"></i>
                                    {{ $placePhone }}
                                </a>
                            @endif
                            @if($placeEmail)
                                <a href="mailto:{{ $placeEmail }}" class="flex items-center gap-2 text-accent-600 hover:underline">
                                    <i class="fa-solid fa-envelope text-accent-600"></i>
                                    {{ $placeEmail }}
                                </a>
                            @endif
                            @if($placeWebsite)
                                <a href="{{ $placeWebsite }}" target="_blank" rel="noopener" class="flex items-center gap-2 text-accent-600 hover:underline">
                                    <i class="fa-solid fa-globe text-accent-600"></i>
                                    {{ $placeWebsiteDisplay }}
                                </a>
                            @endif
                        </div>
                    @endif
                    @if($hasMap)
                        <div id="place-map" class="aspect-video rounded-2xl overflow-hidden border border-slate-200 ring-1 ring-slate-200/50 w-full" style="min-height: 280px;"></div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ (float)$mapLoc['lat'] }},{{ (float)$mapLoc['lng'] }}"
                           target="_blank" rel="noopener noreferrer"
                           class="mt-4 inline-flex items-center gap-2 rounded-xl bg-accent-600 px-5 py-3 text-sm font-semibold text-white hover:bg-accent-700 shadow-lg shadow-accent-600/20 transition">
                            <i class="fa-solid fa-diamond-turn-right text-white"></i>
                            {{ __('messages.buttons.get_directions') }}
                        </a>
                        <script>
                            (function () {
                                var lat = {{ (float)$mapLoc['lat'] }};
                                var lng = {{ (float)$mapLoc['lng'] }};
                                var map = L.map('place-map').setView([lat, lng], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                }).addTo(map);
                                L.marker([lat, lng]).addTo(map);
                            })();
                        </script>
                    @else
                        <div class="aspect-video rounded-2xl bg-secondary/30 flex items-center justify-center text-slate-500 border border-slate-200">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-map text-slate-300"></i> {{ __('messages.places.map_placeholder') }}</span>
                        </div>
                    @endif
                </div>
        </section>
    </article>
@endsection
