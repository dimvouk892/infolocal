@extends('layouts.app')

@php
    $businessTitle = $business->name ?? $business['title'] ?? $business->title ?? '';
    $businessCategory = $business->category?->name ?? $business['category'] ?? '';
    $businessDescription = $business->description ?? $business['description'] ?? '';
    $businessFeaturedImage = $business->featured_image ?? $business['featured_image'] ?? '';
    $businessLogo = $business->logo ?? $business['logo'] ?? '';
    $businessGallery = is_array($business->gallery ?? null) ? $business->gallery : [];
    $businessFeatured = $business->featured ?? $business['featured'] ?? false;
    $businessPhone = trim((string)($business->phone ?? $business['phone'] ?? ''));
    $businessEmail = trim((string)($business->email ?? $business['email'] ?? ''));
    $businessWebsite = trim((string)($business->website ?? $business['website'] ?? ''));
    $businessWebsiteDisplay = __('messages.businesses.visit_website');

    if ($businessWebsite && !preg_match('/^https?:\/\//i', $businessWebsite)) {
        $businessWebsite = 'https://' . $businessWebsite;
    }

    if ($businessWebsite) {
        $host = parse_url($businessWebsite, PHP_URL_HOST);
        if ($host) {
            $businessWebsiteDisplay = preg_replace('/^www\./i', '', $host);
        }
    }

    $businessAddress = $business->address ?? $business['address'] ?? '';
    $businessHours = $business->opening_hours_display ?? $business['opening_hours'] ?? (is_array($business->opening_hours ?? null) ? implode(' | ', $business->opening_hours) : '');
    $businessSocial = is_array($business->social_links ?? $business['social'] ?? null) ? ($business->social_links ?? $business['social']) : [];
    $videoEmbedUrl = $business->video_embed_url ?? null;

    $featuredImageUrl = $businessFeaturedImage
        ? (str_starts_with($businessFeaturedImage, 'http')
            ? $businessFeaturedImage
            : asset('storage/' . ltrim($businessFeaturedImage, '/')))
        : asset('images/placeholder.svg');

    $logoUrl = $businessLogo
        ? (str_starts_with($businessLogo, 'http')
            ? $businessLogo
            : asset('storage/' . ltrim($businessLogo, '/')))
        : asset('images/placeholder.svg');

    $businessReviews = $business instanceof \App\Models\Business
        ? ($business->reviews ?? collect())
        : collect();
    $canAcceptReviews = $business instanceof \App\Models\Business
        && !empty($business->slug)
        && ($business->reviews_enabled ?? true);
    $businessReviewCount = $businessReviews->count();
    $businessReviewAverage = $businessReviewCount > 0 ? round((float) $businessReviews->avg('rating'), 1) : null;
    $showReviewsTab = $canAcceptReviews || $businessReviewCount > 0;
@endphp

@section('title', $businessTitle . ' | ' . __('messages.businesses.meta_title_suffix'))
@section('meta_description', \Illuminate\Support\Str::limit($businessDescription, 160))

@php
    $mapLoc = $business->map_location ?? null;
    $hasMap = is_array($mapLoc) && isset($mapLoc['lat'], $mapLoc['lng']);
@endphp

@if($hasMap)
    @push('head')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @endpush
@endif

@if(!empty($businessGallery))
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
            <img
                src="{{ $featuredImageUrl }}"
                alt="{{ $businessTitle }}"
                class="h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-primary/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/50 to-primary/20"></div>
        </div>

        <div class="relative px-5 py-8 sm:px-8 sm:py-12 lg:px-10 lg:py-14">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex flex-shrink-0 items-start gap-5 sm:gap-6 lg:items-end">
                    <div class="flex h-24 w-24 flex-shrink-0 overflow-hidden rounded-full border-2 border-white/30 bg-white/10 shadow-xl ring-2 ring-white/20 sm:h-32 sm:w-32 lg:h-40 lg:w-40">
                        <img src="{{ $logoUrl }}" alt="{{ $businessTitle }}" class="h-full w-full object-cover">
                    </div>
                    <div class="max-w-2xl min-w-0">
                    @if($businessCategory)
                        <div class="mb-4 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-white backdrop-blur">
                                {{ $businessCategory }}
                            </span>
                        </div>
                    @endif

                    <h1 class="break-words text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {{ $businessTitle }}
                    </h1>

                    @if($businessDescription)
                        <p class="mt-4 max-w-xl text-sm leading-6 text-slate-200 sm:text-base">
                            {{ \Illuminate\Support\Str::limit($businessDescription, 180) }}
                        </p>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabs container --}}
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">
        <div class="border-b border-slate-200 px-3 sm:px-5">
            <nav class="flex items-center justify-center gap-2 overflow-x-auto sm:gap-4">
                <button
                    type="button"
                    data-tab="info"
                    class="tab-btn inline-flex items-center gap-2 border-b-2 border-accent-600 px-4 py-4 text-sm font-semibold text-accent-600 transition"
                >
                                <i class="fa-solid fa-circle-info text-xs text-accent-600"></i>
                    <span>Info</span>
                </button>

                <button
                    type="button"
                    data-tab="gallery"
                    class="tab-btn inline-flex items-center gap-2 border-b-2 border-transparent px-4 py-4 text-sm font-semibold text-slate-500 transition hover:text-slate-700"
                >
                                <i class="fa-solid fa-table-cells-large text-xs text-accent-600"></i>
                    <span>Gallery</span>
                </button>

                <button
                    type="button"
                    data-tab="location"
                    class="tab-btn inline-flex items-center gap-2 border-b-2 border-transparent px-4 py-4 text-sm font-semibold text-slate-500 transition hover:text-slate-700"
                >
                                <i class="fa-solid fa-location-dot text-xs text-accent-600"></i>
                    <span>Location</span>
                </button>

                @if($showReviewsTab)
                    <button
                        type="button"
                        data-tab="reviews"
                        class="tab-btn inline-flex items-center gap-2 border-b-2 border-transparent px-4 py-4 text-sm font-semibold text-slate-500 transition hover:text-slate-700"
                    >
                        <i class="fa-solid fa-star text-xs text-accent-600"></i>
                        <span>Reviews</span>
                        @if($businessReviewCount)
                            <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-accent-50 px-2 py-0.5 text-[11px] font-bold text-accent-700">{{ $businessReviewCount }}</span>
                        @endif
                    </button>
                @endif
            </nav>
        </div>

        <div class="p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <x-ui.alert variant="success" class="mb-6">{{ session('success') }}</x-ui.alert>
            @endif
            @if(session('error'))
                <x-ui.alert variant="error" class="mb-6">{{ session('error') }}</x-ui.alert>
            @endif

            {{-- INFO --}}
            <div id="tab-info" class="tab-content space-y-6">
                <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                    {{-- Main info --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="px-4 py-3 sm:px-5">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($businessCategory)
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        <i class="fa-solid fa-tag text-accent-600"></i>
                                        {{ $businessCategory }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="mt-3 text-xl font-black tracking-tight text-slate-900 sm:text-2xl">
                                {{ $businessTitle }}
                            </h2>

                            @if($businessDescription)
                                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600 sm:text-base">
                                    {{ $businessDescription }}
                                </p>
                            @else
                                <p class="mt-3 text-sm italic text-slate-500">
                                    {{ __('messages.businesses.about_paragraph') }}
                                </p>
                            @endif
                        </div>

                        <div class="space-y-2 border-t border-slate-100 p-4 sm:p-5">
                            @if($businessPhone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $businessPhone) }}" class="flex items-start gap-3 py-1.5 hover:opacity-80 transition">
                                    <i class="fa-solid fa-phone text-accent-600 mt-0.5 w-5 shrink-0 text-center"></i>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ __('messages.businesses.info_phone') }}</p>
                                        <span class="text-sm font-medium text-slate-900 break-all">{{ $businessPhone }}</span>
                                    </div>
                                </a>
                            @endif

                            @if($businessEmail)
                                <a href="mailto:{{ $businessEmail }}" class="flex items-start gap-3 py-1.5 hover:opacity-80 transition">
                                    <i class="fa-solid fa-envelope text-accent-600 mt-0.5 w-5 shrink-0 text-center"></i>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ __('messages.businesses.info_email') }}</p>
                                        <span class="text-sm font-medium text-slate-900 break-all">{{ $businessEmail }}</span>
                                    </div>
                                </a>
                            @endif

                            @if($businessWebsite)
                                <a href="{{ $businessWebsite }}" target="_blank" rel="noopener" class="flex items-start gap-3 py-1.5 hover:opacity-80 transition">
                                    <i class="fa-solid fa-globe text-accent-600 mt-0.5 w-5 shrink-0 text-center"></i>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ __('messages.dashboard.website') }}</p>
                                        <span class="text-sm font-medium text-slate-900 break-all">{{ $businessWebsiteDisplay }}</span>
                                    </div>
                                </a>
                            @endif

                            @if($businessHours)
                                <div class="flex items-start gap-3 py-1.5">
                                    <i class="fa-solid fa-clock text-accent-600 mt-0.5 w-5 shrink-0 text-center"></i>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ __('messages.businesses.info_hours') }}</p>
                                        <p class="mt-0.5 text-sm font-medium text-slate-900 break-words leading-6">{{ $businessHours }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Side cards --}}
                    <div class="space-y-4">
                        @if($businessAddress)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-location-dot text-accent-600 mt-0.5 w-5 shrink-0 text-center"></i>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{{ __('messages.dashboard.address') }}</p>
                                        <p class="mt-1.5 break-words text-sm font-medium leading-5 text-slate-900">{{ $businessAddress }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($businessSocial))
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.14em] text-slate-500">
                                    <i class="fa-solid fa-share-nodes text-accent-600"></i>
                                    {{ __('messages.businesses.social_media_title') }}
                                </h3>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($businessSocial as $network => $url)
                                        @php
                                            $icons = [
                                                'facebook' => 'fa-brands fa-facebook-f',
                                                'instagram' => 'fa-brands fa-instagram',
                                                'twitter' => 'fa-brands fa-twitter',
                                                'x' => 'fa-brands fa-x-twitter',
                                                'linkedin' => 'fa-brands fa-linkedin-in',
                                                'youtube' => 'fa-brands fa-youtube',
                                                'tiktok' => 'fa-brands fa-tiktok',
                                                'pinterest' => 'fa-brands fa-pinterest-p',
                                                'whatsapp' => 'fa-brands fa-whatsapp',
                                            ];
                                            $icon = $icons[strtolower($network)] ?? 'fa-solid fa-link';
                                        @endphp
                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-accent-200 hover:bg-accent-50 hover:text-accent-800"
                                        >
                                            <i class="{{ $icon }} text-accent-600 group-hover:text-accent-700"></i>
                                            {{ ucfirst($network) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- GALLERY --}}
            <div id="tab-gallery" class="tab-content hidden space-y-6">
                @if($videoEmbedUrl)
                    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                        <div class="mb-4 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-600">
                                <i class="fa-solid fa-video text-accent-600"></i>
                                Video
                            </span>
                        </div>

                        <div class="aspect-video overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                            <iframe
                                src="{{ $videoEmbedUrl }}?rel=0"
                                class="h-full w-full"
                                allowfullscreen
                                title="{{ $businessTitle }}"
                            ></iframe>
                        </div>
                    </div>
                @endif

                @if(!empty($businessGallery))
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-3">
                        @foreach($businessGallery as $path)
                            @php
                                $fullUrl = str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'));
                            @endphp

                            <a
                                href="{{ $fullUrl }}"
                                class="group relative block aspect-square overflow-hidden rounded-2xl bg-slate-100"
                                data-glightbox="title: {{ e($businessTitle) }}; description: {{ e(\Illuminate\Support\Str::limit($businessDescription, 80)) }}"
                            >
                                <x-image-or-placeholder
                                    :src="$path"
                                    :alt="$businessTitle"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                />
                                <div class="absolute inset-0 bg-slate-900/0 transition group-hover:bg-slate-900/10"></div>
                            </a>
                        @endforeach
                    </div>
                @elseif(!$videoEmbedUrl)
                    <div class="flex min-h-[280px] flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm">
                            <i class="fa-solid fa-image text-xl text-accent-600"></i>
                        </div>
                        <h2 class="mt-4 text-lg font-bold text-slate-900">
                            {{ __('messages.businesses.gallery_title') }}
                        </h2>
                        <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">
                            {{ __('messages.businesses.gallery_placeholder') }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- LOCATION --}}
            <div id="tab-location" class="tab-content hidden space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-bold text-slate-900">
                        {{ __('messages.businesses.location_title') }}
                    </h2>

                    @if($businessAddress)
                        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                            {{ $businessAddress }}
                        </p>
                    @endif

                    @if($hasMap)
                        <div
                            id="business-map"
                            class="mt-5 aspect-video w-full overflow-hidden rounded-2xl border border-accent-200 shadow-sm"
                            style="min-height: 280px;"
                        ></div>

                        <div class="mt-5">
                            <a
                                href="https://www.google.com/maps/dir/?api=1&destination={{ (float)$mapLoc['lat'] }},{{ (float)$mapLoc['lng'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-accent-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-accent-700"
                            >
                                <i class="fa-solid fa-diamond-turn-right text-white"></i>
                                {{ __('messages.buttons.get_directions') }}
                            </a>
                            <p class="mt-2 text-xs text-slate-500">
                                {{ __('messages.businesses.get_directions_help') }}
                            </p>
                        </div>
                    @else
                        <div class="mt-5 flex min-h-[280px] items-center justify-center rounded-2xl bg-slate-100 text-sm text-slate-400">
                            {{ __('messages.businesses.map_placeholder') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- REVIEWS --}}
            @if($showReviewsTab)
                <div id="tab-reviews" class="tab-content hidden space-y-6">
                    <div id="reviews" class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                        <div class="space-y-4">
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Visitor Reviews</p>
                                        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-900">What visitors say</h2>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">Visitors can leave a rating and a comment for this business.</p>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-center sm:min-w-[170px]">
                                        <div class="text-3xl font-black text-slate-900">{{ $businessReviewAverage ? number_format($businessReviewAverage, 1) : '0.0' }}</div>
                                        <div class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Average rating</div>
                                        <div class="mt-2 flex items-center justify-center gap-1 text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $businessReviewAverage && $i <= round($businessReviewAverage) ? 'opacity-100' : 'opacity-30' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="mt-2 text-sm text-slate-500">{{ $businessReviewCount }} review{{ $businessReviewCount === 1 ? '' : 's' }}</div>
                                    </div>
                                </div>
                            </div>

                            @forelse($businessReviews as $review)
                                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h3 class="text-base font-bold text-slate-900">{{ $review->reviewer_name }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">{{ optional($review->created_at)->format('d M Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= (int) $review->rating ? 'opacity-100' : 'opacity-25' }}"></i>
                                            @endfor
                                            <span class="ml-2 text-sm font-semibold text-slate-700">{{ (int) $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <div class="flex min-h-[220px] flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 text-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm">
                                        <i class="fa-solid fa-comments text-xl text-accent-600"></i>
                                    </div>
                                    <h3 class="mt-4 text-lg font-bold text-slate-900">No reviews yet</h3>
                                    <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">Be the first visitor to rate and comment on this business.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="lg:sticky lg:top-24">
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                                <div class="mb-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ __('messages.reviews.add_review') }}</p>
                                    <h3 class="mt-2 text-xl font-black tracking-tight text-slate-900">{{ __('messages.reviews.leave_comment') }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ __('messages.reviews.rate_and_share') }}</p>
                                </div>

                                @if($canAcceptReviews && $errors->any())
                                    <x-ui.alert variant="error" class="mb-4">
                                        {{ __('messages.reviews.form_complete_correctly') }}
                                    </x-ui.alert>
                                @endif

                                @if($canAcceptReviews)
                                    <form method="POST" action="{{ route('businesses.reviews.store', $business->slug) }}" class="space-y-4">
                                        @csrf

                                        <x-ui.input
                                            :label="__('messages.reviews.your_name')"
                                            name="reviewer_name"
                                            :value="old('reviewer_name')"
                                            :error="$errors->first('reviewer_name')"
                                            :placeholder="__('messages.reviews.full_name_placeholder')"
                                        />

                                        <x-ui.input
                                            :label="__('messages.dashboard.email')"
                                            name="reviewer_email"
                                            type="email"
                                            :value="old('reviewer_email')"
                                            :error="$errors->first('reviewer_email')"
                                            placeholder="name@example.com"
                                        />
                                        <p class="mt-1 text-xs text-slate-500">{{ __('messages.reviews.one_review_per_email') }}</p>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-700">{{ __('messages.reviews.rating') }}</label>
                                            <select name="rating" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                                <option value="">{{ __('messages.reviews.select_rating') }}</option>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}" {{ (string) old('rating') === (string) $i ? 'selected' : '' }}>
                                                        {{ $i }} {{ trans_choice('messages.reviews.stars', $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @if($errors->has('rating'))
                                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('rating') }}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-700">{{ __('messages.reviews.comment') }}</label>
                                            <textarea name="comment" rows="6" class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.reviews.comment_placeholder') }}">{{ old('comment') }}</textarea>
                                            @if($errors->has('comment'))
                                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('comment') }}</p>
                                            @endif
                                        </div>

                                        <x-ui.button type="submit" class="w-full">
                                            {{ __('messages.reviews.submit_review') }}
                                        </x-ui.button>
                                    </form>
                                @else
                                    <x-ui.alert variant="info">
                                        {{ __('messages.reviews.reviews_disabled') }}
                                    </x-ui.alert>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
</article>

@if(!empty($businessGallery))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof GLightbox !== 'undefined') {
                GLightbox({ selector: '[data-glightbox]' });
            }
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.tab-btn');
        const tabs = document.querySelectorAll('.tab-content');
        const hashTabMap = {
            '#reviews': 'reviews',
            '#location': 'location',
            '#gallery': 'gallery',
            '#info': 'info',
        };
        const shouldOpenReviews = @js($showReviewsTab && ($errors->has('reviewer_name') || $errors->has('reviewer_email') || $errors->has('rating') || $errors->has('comment') || old('reviewer_name') || old('reviewer_email') || old('rating') || old('comment')));

        function activateTab(target) {
            if (!document.getElementById('tab-' + target)) {
                target = 'info';
            }

            tabs.forEach((tab) => tab.classList.add('hidden'));

            buttons.forEach((btn) => {
                btn.classList.remove('border-accent-600', 'text-accent-600');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            const targetTab = document.getElementById('tab-' + target);
            const activeButton = document.querySelector('.tab-btn[data-tab="' + target + '"]');

            if (targetTab) {
                targetTab.classList.remove('hidden');
            }

            if (activeButton) {
                activeButton.classList.remove('border-transparent', 'text-slate-500');
                activeButton.classList.add('border-accent-600', 'text-accent-600');
            }

            if (target === 'location' && typeof L !== 'undefined' && !window.businessMapInitialized) {
                @if($hasMap)
                    const lat = {{ (float)$mapLoc['lat'] }};
                    const lng = {{ (float)$mapLoc['lng'] }};

                    const map = L.map('business-map').setView([lat, lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    L.marker([lat, lng]).addTo(map);
                    window.businessMapInitialized = true;

                    setTimeout(() => {
                        map.invalidateSize();
                    }, 200);
                @endif
            }
        }

        buttons.forEach((button) => {
            button.addEventListener('click', function () {
                const target = this.dataset.tab;
                activateTab(target);
                history.replaceState(null, '', target === 'info' ? (window.location.pathname + window.location.search) : '#' + target);
            });
        });

        activateTab(hashTabMap[window.location.hash] || (shouldOpenReviews ? 'reviews' : 'info'));
    });
</script>
@endsection