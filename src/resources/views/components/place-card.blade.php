@props(['place', 'variant' => 'default'])

@php
    $slug = $place->slug ?? $place['slug'];
    $featuredImage = $place->featured_image ?? $place['featured_image'] ?? '';
    $title = $place->title ?? $place['title'] ?? '';
    $category = $place->category?->name ?? $place['category'] ?? $place['category_name'] ?? '';
    $description = $place->short_description ?? $place['short_description'] ?? $place['description'] ?? '';
    $address = $place->address ?? $place['address'] ?? '';
    $featured = $place->featured ?? $place['featured'] ?? false;
@endphp

<article class="group rounded-2xl overflow-hidden bg-white shadow-sm border border-stone-200/80 flex flex-col hover:shadow-xl hover:border-amber-200/60 transition-all duration-300">
    <a href="{{ route('places.show', $slug) }}" class="block aspect-[4/3] relative overflow-hidden bg-secondary/50 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-inset">
        <x-image-or-placeholder :src="$featuredImage" :alt="$title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
    </a>
    <div class="flex-1 flex flex-col p-5 space-y-3 bg-white">
        @if($category)
            <div class="flex items-center text-xs font-semibold text-accent uppercase tracking-wide">
                <span>{{ $category }}</span>
            </div>
        @endif
        <h3 class="text-base font-semibold text-primary group-hover:text-accent transition-colors">
            <a href="{{ route('places.show', $slug) }}">
                {{ $title }}
            </a>
        </h3>
        @if($description)
            <p class="text-sm text-slate-600 line-clamp-3">
                {{ Str::limit($description, 120) }}
            </p>
        @endif
        @if($address)
            <div class="mt-2 flex items-start space-x-2 text-xs text-slate-500">
                <svg class="h-4 w-4 flex-none shrink-0 mt-0.5 text-accent/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z" />
                    <circle cx="12" cy="11" r="2.5" />
                </svg>
                <span class="line-clamp-2">{{ $address }}</span>
            </div>
        @endif
        <div class="pt-3 mt-auto">
            <a href="{{ route('places.show', $slug) }}"
               class="inline-flex items-center text-sm font-semibold text-accent hover:text-accent-hover transition-colors">
                {{ __('messages.buttons.view_details') }}
                <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</article>
