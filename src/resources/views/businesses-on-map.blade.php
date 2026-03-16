@extends('layouts.app')

@section('title', __('messages.on_map.meta_title'))
@section('meta_description', __('messages.on_map.meta_description'))

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .category-pin-marker {
            background: transparent;
            border: 0;
        }

        .category-pin-marker svg {
            display: block;
            width: 42px;
            height: 52px;
        }
    </style>
@endpush

@section('content')
    <section class="space-y-6 min-w-0 overflow-hidden">
        <x-ui.section-header
            :title="__('messages.on_map.title')"
            :subtitle="__('messages.on_map.subtitle')"
        />

        @if($businesses->isEmpty() && $places->isEmpty())
            <x-ui.card class="py-8 text-center">
                <p class="text-sm text-slate-500">{{ __('messages.on_map.empty') }}</p>
            </x-ui.card>
        @else
            <div id="map" class="relative z-0 h-[320px] w-full max-w-full overflow-hidden rounded-2xl border border-slate-200 sm:h-[420px] lg:h-[520px]"></div>

            <x-ui.card>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="map-filter-type" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.on_map.content_type') }}</label>
                            <select id="map-filter-type" class="block w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-accent focus:ring-accent">
                                <option value="all" {{ $selectedType === 'all' ? 'selected' : '' }}>{{ __('messages.filters.all') }}</option>
                                <option value="business" {{ $selectedType === 'business' ? 'selected' : '' }}>{{ __('messages.nav.businesses') }}</option>
                                <option value="place" {{ $selectedType === 'place' ? 'selected' : '' }}>{{ __('messages.on_map.filter_places') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="map-filter-business-category" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.on_map.business_category') }}</label>
                            <select id="map-filter-business-category" class="block w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-accent focus:ring-accent">
                                <option value="">{{ __('messages.on_map.all_business_categories') }}</option>
                                @foreach($businessCategories as $category)
                                    <option value="{{ $category->slug }}" {{ $selectedBusinessCategory === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="map-filter-place-category" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.on_map.place_category') }}</label>
                            <select id="map-filter-place-category" class="block w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-accent focus:ring-accent">
                                <option value="">{{ __('messages.on_map.all_place_categories') }}</option>
                                @foreach($placeCategories as $category)
                                    <option value="{{ $category->slug }}" {{ $selectedPlaceCategory === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.on_map.visible_pins') }}</p>
                            <p id="map-visible-count" class="mt-1 text-lg font-semibold text-primary">{{ count($mapPins) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="inline-flex items-center gap-2 rounded-full bg-secondary px-3 py-1.5 text-primary">
                            <span class="h-2.5 w-2.5 rounded-full bg-accent"></span>
                            {{ __('messages.on_map.map_legend_businesses_places') }}
                        </span>
                        <button type="button" id="map-clear-filters" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-primary/30 hover:text-primary">
                            {{ __('messages.buttons.clear_filters') }}
                        </button>
                    </div>
                </div>
            </x-ui.card>

            <script>
            (function () {
                var pins = @json($mapPins);
                var iconPaths = @json(\App\Support\MapPin::paths());
                var mapI18n = @json(['place' => __('messages.on_map.place'), 'business' => __('messages.on_map.business'), 'directions' => __('messages.on_map.directions')]);
                if (pins.length === 0) return;
                var typeFilter = document.getElementById('map-filter-type');
                var businessCategoryFilter = document.getElementById('map-filter-business-category');
                var placeCategoryFilter = document.getElementById('map-filter-place-category');
                var clearFiltersBtn = document.getElementById('map-clear-filters');
                var visibleCount = document.getElementById('map-visible-count');
                var markerLayer = L.layerGroup();

                function escapeHtml(s) {
                    if (!s) return '';
                    var div = document.createElement('div');
                    div.textContent = s;
                    return div.innerHTML;
                }

                function directionsUrl(pin) {
                    return 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(pin.lat + ',' + pin.lng);
                }

                function createCategoryIcon(pin) {
                    var glyph = iconPaths[pin.category_icon] || iconPaths['map-pin'];
                    var color = /^#[0-9A-Fa-f]{6}$/.test(pin.category_color || '') ? pin.category_color : '#10B981';
                    var svg = ''
                        + '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 56" fill="none" aria-hidden="true">'
                        + '<path d="M24 3C14.611 3 7 10.611 7 20c0 11.83 13.496 24.84 16.018 27.153a1.5 1.5 0 0 0 1.964 0C27.504 44.84 41 31.83 41 20 41 10.611 33.389 3 24 3Z" fill="' + escapeHtml(color) + '" stroke="white" stroke-width="3"/>'
                        + '<circle cx="24" cy="20" r="11" fill="white" fill-opacity=".16"/>'
                        + '<path d="' + escapeHtml(glyph) + '" fill="white" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>'
                        + '</svg>';

                    return L.divIcon({
                        className: 'category-pin-marker',
                        html: svg,
                        iconSize: [42, 52],
                        iconAnchor: [21, 50],
                        popupAnchor: [0, -42],
                    });
                }

                function popupContent(p) {
                    var html = '<div class="min-w-[180px] text-sm">';
                    html += '<div class="font-semibold text-slate-900">' + escapeHtml(p.name) + '</div>';
                    html += '<div class="mt-1 inline-flex rounded-full bg-secondary px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-primary">' + escapeHtml(p.type === 'place' ? mapI18n.place : mapI18n.business) + '</div>';
                    if (p.category) html += '<div class="text-xs text-accent mt-0.5">' + escapeHtml(p.category) + '</div>';
                    if (p.address) html += '<div class="text-xs text-slate-600 mt-1">' + escapeHtml(p.address) + '</div>';
                    if (p.phone) html += '<div class="text-xs text-slate-600 mt-0.5">' + escapeHtml(p.phone) + '</div>';
                    html += '<div class="mt-3 flex flex-wrap gap-2">';
                    html += '<a href="' + escapeHtml(p.url) + '" class="inline-flex items-center rounded-full bg-primary px-3 py-1.5 text-xs font-semibold text-white">' + escapeHtml('{{ __("messages.buttons.view_details") }}') + '</a>';
                    html += '<a href="' + escapeHtml(directionsUrl(p)) + '" target="_blank" rel="noopener" class="inline-flex items-center rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700">' + escapeHtml(mapI18n.directions) + '</a>';
                    html += '</div>';
                    html += '</div>';
                    return html;
                }

                function filterPins() {
                    var selectedType = typeFilter ? typeFilter.value : 'all';
                    var selectedBusinessCategory = businessCategoryFilter ? businessCategoryFilter.value : '';
                    var selectedPlaceCategory = placeCategoryFilter ? placeCategoryFilter.value : '';

                    return pins.filter(function (pin) {
                        if (selectedType !== 'all' && pin.type !== selectedType) {
                            return false;
                        }

                        if (pin.type === 'business' && selectedBusinessCategory && pin.category_slug !== selectedBusinessCategory) {
                            return false;
                        }

                        if (pin.type === 'place' && selectedPlaceCategory && pin.category_slug !== selectedPlaceCategory) {
                            return false;
                        }

                        return true;
                    });
                }

                function renderPins() {
                    var filteredPins = filterPins();
                    markerLayer.clearLayers();

                    filteredPins.forEach(function (pin) {
                        L.marker([pin.lat, pin.lng], { icon: createCategoryIcon(pin) })
                            .bindPopup(popupContent(pin))
                            .addTo(markerLayer);
                    });

                    if (visibleCount) {
                        visibleCount.textContent = filteredPins.length;
                    }

                    if (filteredPins.length === 1) {
                        map.setView([filteredPins[0].lat, filteredPins[0].lng], 14);
                        return;
                    }

                    if (filteredPins.length > 1) {
                        var bounds = L.latLngBounds(filteredPins.map(function (pin) {
                            return [pin.lat, pin.lng];
                        }));
                        map.fitBounds(bounds.pad(0.12));
                    }
                }

                var center = pins[0];
                var map = L.map('map').setView([center.lat, center.lng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                markerLayer.addTo(map);
                renderPins();

                [typeFilter, businessCategoryFilter, placeCategoryFilter].forEach(function (input) {
                    if (!input) return;
                    input.addEventListener('change', renderPins);
                });

                if (clearFiltersBtn) {
                    clearFiltersBtn.addEventListener('click', function () {
                        if (typeFilter) typeFilter.value = 'all';
                        if (businessCategoryFilter) businessCategoryFilter.value = '';
                        if (placeCategoryFilter) placeCategoryFilter.value = '';
                        renderPins();
                    });
                }
            })();
            </script>
        @endif
    </section>
@endsection
