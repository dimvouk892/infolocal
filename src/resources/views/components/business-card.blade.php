@props(['business', 'variant' => 'default'])

@php
    $slug = $business->slug ?? $business['slug'];
    $logo = $business->logo ?? $business['logo'] ?? '';
    $title = $business->name ?? $business['title'] ?? $business->title ?? '';
    $category = $business->category?->name ?? $business['category'] ?? $business->category_name ?? '';
    $description = $business->description ?? $business['description'] ?? '';
    $address = $business->address ?? $business['address'] ?? '';
    $openingHours = $business->opening_hours_display ?? $business['opening_hours'] ?? (is_array($business->opening_hours ?? null) ? implode(' | ', $business->opening_hours) : '');
    $phone = $business->phone ?? $business['phone'] ?? '';
@endphp

@if($variant === 'instagram')
    {{-- Card: logo on top, then category + title + content --}}
    <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200/50 transition-shadow hover:ring-slate-300">
        <a href="{{ route('businesses.show', $slug) }}" class="flex flex-col focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-inset">
            {{-- Logo area: fixed square, logo fills box (smaller side fits, no empty space) --}}
            <div class="relative aspect-square w-full overflow-hidden bg-slate-50">
                @if($logo)
                    <x-image-or-placeholder :src="$logo" :alt="$title" class="h-full w-full object-cover" />
                @else
                    <span class="absolute inset-0 flex items-center justify-center text-3xl font-bold tracking-tight text-slate-300">{{ strtoupper(mb_substr($title, 0, 2)) }}</span>
                @endif
            </div>
        </a>
        {{-- Content: category label, title, then rest --}}
        <div class="flex flex-1 flex-col p-4 sm:p-5">
            @if($category)
                <span class="text-xs font-semibold uppercase tracking-wider text-accent-600">{{ $category }}</span>
            @endif
            <h3 class="mt-1 text-base font-semibold text-slate-900 sm:text-lg">
                <a href="{{ route('businesses.show', $slug) }}" class="hover:text-accent-700">{{ $title }}</a>
            </h3>
            <p class="mt-2 text-xs text-slate-600 line-clamp-2 sm:line-clamp-3">
                {{ Str::limit($description, 100) }}
            </p>
            @if($address)
                <div class="mt-3 flex items-start gap-1.5 text-xs text-slate-500">
                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                    <span class="line-clamp-1">{{ $address }}</span>
                </div>
            @endif
            <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                @if($openingHours)
                    <span class="line-clamp-1">{{ $openingHours }}</span>
                @endif
                @if($phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="shrink-0 font-medium text-accent-600 hover:text-accent-700">{{ $phone }}</a>
                @endif
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                <a href="{{ route('businesses.show', $slug) }}" class="inline-flex items-center text-xs font-semibold text-accent-700 hover:text-accent-800">
                    {{ __('messages.buttons.view_details') }}
                    <svg class="ml-0.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                @if($phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="inline-flex items-center rounded-full border border-accent-100 bg-accent-50 px-2.5 py-1 text-xs font-semibold text-accent-700 hover:bg-accent-100">
                        {{ __('messages.buttons.contact_business') }}
                    </a>
                @endif
            </div>
        </div>
    </article>
@else
    {{-- Default card (e.g. home promoted): same structure, slightly larger typography --}}
    <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md">
        <a href="{{ route('businesses.show', $slug) }}" class="flex flex-col focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-inset">
            {{-- Logo area: fixed box, logo fills (smaller side fits, no empty space) --}}
            <div class="relative h-44 w-full overflow-hidden bg-slate-50 sm:h-52">
                @if($logo)
                    <x-image-or-placeholder :src="$logo" :alt="$title" class="h-full w-full object-cover" />
                @else
                    <span class="absolute inset-0 flex items-center justify-center text-4xl font-bold tracking-tight text-slate-300">{{ strtoupper(mb_substr($title, 0, 2)) }}</span>
                @endif
            </div>
        </a>
        <div class="flex flex-1 flex-col p-5">
            @if($category)
                <span class="text-xs font-semibold uppercase tracking-wider text-accent-600">{{ $category }}</span>
            @endif
            <h3 class="mt-1 text-lg font-semibold text-slate-900 sm:text-xl">
                <a href="{{ route('businesses.show', $slug) }}" class="hover:text-accent-700">{{ $title }}</a>
            </h3>
            <p class="mt-3 text-sm text-slate-600 line-clamp-3">
                {{ Str::limit($description, 120) }}
            </p>
            @if($address)
                <div class="mt-3 flex items-start gap-2 text-xs text-slate-500">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                    <span>{{ $address }}</span>
                </div>
            @endif
            <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                <span>{{ $openingHours }}</span>
                @if($phone)
                    <span>{{ $phone }}</span>
                @endif
            </div>
            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('businesses.show', $slug) }}" class="inline-flex items-center text-sm font-semibold text-accent-700 hover:text-accent-800">
                    {{ __('messages.buttons.view_details') }}
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                @if($phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="inline-flex items-center rounded-full border border-accent-100 bg-accent-50 px-3 py-1 text-xs font-semibold text-accent-700 hover:bg-accent-100">
                        {{ __('messages.buttons.contact_business') }}
                    </a>
                @endif
            </div>
        </div>
    </article>
@endif
