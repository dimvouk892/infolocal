@extends('layouts.app')

@section('title', __('messages.businesses.meta_title'))
@section('meta_description', __('messages.businesses.meta_description'))

@section('content')
    <section class="min-w-0 py-10 sm:py-14 businesses-listing-section">
        <div class="listing-header-effekt">
        <x-ui.section-header
            :title="__('messages.businesses.title')"
            :subtitle="__('messages.businesses.subtitle')"
        >
            <x-slot:action>
                <form method="GET" action="{{ route('businesses') }}" id="businesses-filter-form" class="flex flex-wrap items-center gap-2">
                    @if(isset($villages) && $villages->isNotEmpty())
                    <select name="village" class="businesses-filter-select block rounded-lg border-slate-300 bg-white text-sm text-slate-700 py-2 px-4 shadow-sm focus:border-accent focus:ring-accent min-w-[160px]">
                        <option value="">{{ __('messages.places.filter_all_villages') }}</option>
                        @foreach($villages as $v)
                            <option value="{{ $v->slug }}" {{ ($currentVillage ?? '') === $v->slug ? 'selected' : '' }}>{{ $v->name }}</option>
                        @endforeach
                    </select>
                    @endif
                    <select name="category" class="businesses-filter-select block rounded-lg border-slate-300 bg-white text-sm text-slate-700 py-2 px-4 shadow-sm focus:border-accent focus:ring-accent min-w-[160px]">
                        <option value="">{{ __('messages.businesses.filter_all') }}</option>
                        @foreach($categories as $slug => $name)
                            <option value="{{ $slug }}" {{ $currentCategory === $slug ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-ui.button type="submit" size="sm">{{ __('messages.buttons.apply_filter') }}</x-ui.button>
                    @if($currentCategory || ($currentVillage ?? ''))
                        <a href="{{ route('businesses') }}" class="text-xs text-slate-500 hover:text-accent">{{ __('messages.buttons.clear_filter') }}</a>
                    @endif
                </form>
            </x-slot:action>
        </x-ui.section-header>
        </div>

        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 md:gap-5">
            @forelse($businesses as $business)
                <x-business-card :business="$business" variant="instagram" />
            @empty
                <div class="col-span-full">
                    <x-ui.card class="py-12 text-center">
                        <p class="text-sm text-slate-500">
                            {{ __('messages.businesses.empty_state') }}
                        </p>
                    </x-ui.card>
                </div>
            @endforelse
        </div>
    </section>
    <style>
        .listing-header-effekt { opacity: 0; transform: translateY(12px); transition: opacity 0.5s ease-out, transform 0.5s ease-out; }
        .listing-header-effekt.is-visible { opacity: 1; transform: translateY(0); }
    </style>
    <script>
        (function () {
            var el = document.querySelector('.businesses-listing-section .listing-header-effekt');
            if (el) {
                requestAnimationFrame(function () { el.classList.add('is-visible'); });
            }
            var form = document.getElementById('businesses-filter-form');
            if (form) {
                form.querySelectorAll('.businesses-filter-select').forEach(function (select) {
                    select.addEventListener('change', function () { form.submit(); });
                });
            }
        })();
    </script>
@endsection
