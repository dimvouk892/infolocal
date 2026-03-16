@unless(request()->routeIs('login', 'register', 'password.request', 'password.reset'))
@php
    $frontendLogoHeight = max(24, min(220, (int) (optional($settings)->logo_frontend_height ?? 80)));
    $frontendLogoMaxWidth = max(60, min(480, (int) (optional($settings)->logo_frontend_max_width ?? 260)));
    $frontendLogoAlignment = optional($settings)->logo_frontend_alignment === 'center' ? 'justify-center' : 'justify-start';
@endphp
<header class="relative z-[500]" id="site-header" style="background-color: var(--app-header-bg); color: var(--app-header-text);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-visible">
        <div class="flex items-center justify-between min-h-[5.5rem] sm:min-h-[6.25rem] overflow-visible py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group {{ $frontendLogoAlignment }}">
                @if(optional($settings)->logo_frontend)
                    @php $logo = $settings->logo_frontend; @endphp
                    <img src="{{ (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) ? $logo : asset('storage/' . ltrim($logo, '/')) }}" alt="{{ optional($settings)->site_title ?? __('messages.brand.name') }}" class="w-auto object-contain transition-transform group-hover:scale-[1.02]" style="height: {{ $frontendLogoHeight }}px; max-width: {{ $frontendLogoMaxWidth }}px;">
                @else
                    <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-accent flex items-center justify-center text-white font-bold shadow-lg text-base sm:text-lg shrink-0">
                        {{ strtoupper(substr(optional($settings)->site_title ?? __('messages.brand.name'), 0, 2)) }}
                    </div>
                @endif
            </a>

            {{-- Hamburger: only on mobile --}}
            <button type="button"
                    id="menu-toggle"
                    class="md:hidden inline-flex items-center justify-center w-12 h-12 rounded-lg transition"
                    style="color: color-mix(in srgb, var(--app-header-text) 90%, transparent);"
                    aria-label="{{ __('messages.nav.menu_toggle') }}"
                    aria-expanded="false"
                    aria-controls="mobile-menu">
                <svg class="w-7 h-7 menu-icon-open" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg class="w-7 h-7 menu-icon-close hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <nav class="site-nav hidden md:flex items-center space-x-7 text-sm font-semibold overflow-visible">
                <a href="{{ route('home') }}"
                   class="py-2 transition {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                    {{ __('messages.nav.home') }}
                </a>

                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('places.index') }}"
                       class="block py-2 transition {{ request()->routeIs('places.*') ? 'nav-active' : '' }}">
                        {{ __('messages.nav.places_to_visit') }}
                    </a>
                    <div x-show="open" x-transition
                         class="absolute left-0 top-full pt-2 z-50">
                        <div class="site-menu-panel min-w-[220px] rounded-xl border py-2">
                        <a href="{{ route('places.index') }}"
                           class="block px-4 py-2.5 text-sm transition {{ !request()->query('category') ? 'bg-secondary text-primary font-semibold' : 'hover:bg-white/70 hover:text-primary' }}">
                            {{ __('messages.filters.all') }}
                        </a>
                        @foreach($placeCategories ?? [] as $cat)
                            <a href="{{ route('places.index', ['category' => $cat->slug]) }}"
                               class="block px-4 py-2.5 text-sm transition {{ request()->query('category') === $cat->slug ? 'bg-secondary text-primary font-semibold' : 'hover:bg-white/70 hover:text-primary' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('businesses') }}"
                       class="block py-2 transition {{ request()->routeIs('businesses*') ? 'nav-active' : '' }}">
                        {{ __('messages.nav.businesses') }}
                    </a>
                    <div x-show="open" x-transition
                         class="absolute left-0 top-full pt-2 z-50">
                        <div class="site-menu-panel min-w-[220px] rounded-xl border py-2">
                        <a href="{{ route('businesses') }}"
                           class="block px-4 py-2.5 text-sm transition {{ !request()->query('category') ? 'bg-secondary text-primary font-semibold' : 'hover:bg-white/70 hover:text-primary' }}">
                            {{ __('messages.filters.all') }}
                        </a>
                        @foreach($businessCategories ?? [] as $cat)
                            <a href="{{ route('businesses', ['category' => $cat->slug]) }}"
                               class="block px-4 py-2.5 text-sm transition {{ request()->query('category') === $cat->slug ? 'bg-secondary text-primary font-semibold' : 'hover:bg-white/70 hover:text-primary' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('businesses.on_map') }}"
                   class="py-2 transition {{ request()->routeIs('businesses.on_map') ? 'nav-active' : '' }}">
                    {{ __('messages.nav.on_map') }}
                </a>
                <a href="{{ route('about') }}"
                   class="py-2 transition {{ request()->routeIs('about') ? 'nav-active' : '' }}">
                    {{ __('messages.nav.about') }}
                </a>
                <a href="{{ route('contact') }}"
                   class="py-2 transition {{ request()->routeIs('contact') ? 'nav-active' : '' }}">
                    {{ __('messages.nav.contact') }}
                </a>

                <div class="ml-4 flex items-center gap-1 border-l border-white/20 pl-4" aria-label="{{ __('messages.nav.languages') }}">
                    @foreach(config('locales.available', ['en' => 'English', 'el' => 'Ελληνικά']) as $code => $label)
                        <a href="{{ route('language.switch', $code) }}" class="py-1.5 px-2 rounded text-sm font-medium transition {{ app()->getLocale() === $code ? 'bg-white/20' : 'hover:bg-white/10' }}">{{ $code === 'en' ? 'EN' : 'ΕΛ' }}</a>
                    @endforeach
                </div>

                <div class="ml-auto pl-6 relative" id="account-dropdown-wrap">
                    <button type="button" id="account-dropdown-btn" class="inline-flex items-center justify-center w-11 h-11 rounded-xl hover:bg-white/10 transition" aria-expanded="false" aria-haspopup="true" aria-label="{{ auth()->check() ? __('messages.auth.logout') : __('messages.auth.login') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </button>
                    <div id="account-dropdown" class="site-menu-panel hidden absolute right-0 top-full mt-2 w-60 rounded-xl border py-2 z-[520]">
                        @auth
                            <div class="px-4 py-3 border-b" style="border-color: var(--app-menu-border);">
                                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                                <p class="site-menu-muted truncate text-xs">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm transition hover:bg-white/70 hover:text-accent">Admin</a>
                            @else
                                <a href="{{ route('dashboard.index') }}" class="block px-4 py-2.5 text-sm transition hover:bg-white/70 hover:text-accent">{{ __('messages.dashboard.label') }}</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="logout-btn block w-full text-left px-4 py-2.5 text-sm transition hover:bg-white/70 hover:text-accent disabled:opacity-70">
                                    {{ __('messages.auth.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm transition hover:bg-white/70 hover:text-accent">
                                {{ __('messages.auth.login') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>

        {{-- Mobile menu panel --}}
        <div id="mobile-menu"
             class="site-menu-panel hidden md:hidden absolute top-full left-0 right-0 border-t"
             role="dialog"
             aria-label="{{ __('messages.nav.menu_toggle') }}">
            <nav class="px-4 py-5 space-y-0.5 text-base font-semibold">
                <a href="{{ route('home') }}"
                   class="mobile-nav-link block rounded-xl px-4 py-3 transition {{ request()->routeIs('home') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                    {{ __('messages.nav.home') }}
                </a>
                <div x-data="{ open: false }" class="space-y-0.5">
                    <button type="button" @click="open = !open"
                            class="flex items-center justify-between w-full rounded-xl px-4 py-3 text-left transition {{ request()->routeIs('places.*') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                        {{ __('messages.nav.places_to_visit') }}
                        <svg class="w-5 h-5 shrink-0 ml-2 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 mt-1 space-y-0.5 border-l-2 border-accent/40 pl-4">
                        <a href="{{ route('places.index') }}" class="mobile-nav-link block rounded-lg px-4 py-2.5 hover:text-accent {{ !request()->query('category') ? 'text-accent font-semibold' : 'site-menu-muted' }}">{{ __('messages.filters.all') }}</a>
                        @foreach($placeCategories ?? [] as $cat)
                            <a href="{{ route('places.index', ['category' => $cat->slug]) }}" class="mobile-nav-link block rounded-lg px-4 py-2.5 hover:text-accent {{ request()->query('category') === $cat->slug ? 'text-accent font-semibold' : 'site-menu-muted' }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div x-data="{ open: false }" class="space-y-0.5">
                    <button type="button" @click="open = !open"
                            class="flex items-center justify-between w-full rounded-xl px-4 py-3 text-left transition {{ request()->routeIs('businesses*') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                        {{ __('messages.nav.businesses') }}
                        <svg class="w-5 h-5 shrink-0 ml-2 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 mt-1 space-y-0.5 border-l-2 border-accent/40 pl-4">
                        <a href="{{ route('businesses') }}" class="mobile-nav-link block rounded-lg px-4 py-2.5 hover:text-accent {{ !request()->query('category') ? 'text-accent font-semibold' : 'site-menu-muted' }}">{{ __('messages.filters.all') }}</a>
                        @foreach($businessCategories ?? [] as $cat)
                            <a href="{{ route('businesses', ['category' => $cat->slug]) }}" class="mobile-nav-link block rounded-lg px-4 py-2.5 hover:text-accent {{ request()->query('category') === $cat->slug ? 'text-accent font-semibold' : 'site-menu-muted' }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('businesses.on_map') }}"
                   class="mobile-nav-link block rounded-xl px-4 py-3 transition {{ request()->routeIs('businesses.on_map') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                    {{ __('messages.nav.on_map') }}
                </a>
                <a href="{{ route('about') }}"
                   class="mobile-nav-link block rounded-xl px-4 py-3 transition {{ request()->routeIs('about') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                    {{ __('messages.nav.about') }}
                </a>
                <a href="{{ route('contact') }}"
                   class="mobile-nav-link block rounded-xl px-4 py-3 transition {{ request()->routeIs('contact') ? 'bg-secondary text-accent' : 'hover:bg-white/70 hover:text-accent' }}">
                    {{ __('messages.nav.contact') }}
                </a>
                <div class="flex gap-2 px-4 py-3 border-t border-white/10 mt-2 pt-3" aria-label="{{ __('messages.nav.languages') }}">
                    @foreach(config('locales.available', ['en' => 'English', 'el' => 'Ελληνικά']) as $code => $label)
                        <a href="{{ route('language.switch', $code) }}" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ app()->getLocale() === $code ? 'bg-white/20' : 'hover:bg-white/10' }}">{{ $code === 'en' ? 'EN' : 'ΕΛ' }}</a>
                    @endforeach
                </div>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link block rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-accent">Admin</a>
                    @else
                        <a href="{{ route('dashboard.index') }}" class="mobile-nav-link block rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-accent">{{ __('messages.dashboard.label') }}</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 logout-form">
                        @csrf
                        <button type="submit" class="mobile-nav-link logout-btn block w-full rounded-xl px-4 py-3 text-left transition hover:bg-white/70 hover:text-accent disabled:opacity-70">{{ __('messages.auth.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link block rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-accent">{{ __('messages.auth.login') }}</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
<script>
(function () {
    var toggle = document.getElementById('menu-toggle');
    var menu = document.getElementById('mobile-menu');
    var header = document.getElementById('site-header');
    if (!toggle || !menu) return;
    function setOpen(open) {
        menu.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        var openIcon = toggle.querySelector('.menu-icon-open');
        var closeIcon = toggle.querySelector('.menu-icon-close');
        if (openIcon) openIcon.classList.toggle('hidden', open);
        if (closeIcon) closeIcon.classList.toggle('hidden', !open);
    }
    toggle.addEventListener('click', function () { setOpen(menu.classList.contains('hidden')); });
    document.addEventListener('click', function (e) {
        if (!header.contains(e.target) && !menu.classList.contains('hidden')) setOpen(false);
    });
    menu.querySelectorAll('.mobile-nav-link').forEach(function (link) {
        link.addEventListener('click', function () { setOpen(false); });
    });
})();
(function () {
    var wrap = document.getElementById('account-dropdown-wrap');
    var btn = document.getElementById('account-dropdown-btn');
    var panel = document.getElementById('account-dropdown');
    if (!wrap || !btn || !panel) return;
    function setOpen(open) {
        panel.classList.toggle('hidden', !open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(panel.classList.contains('hidden'));
    });
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) setOpen(false);
    });
})();
</script>
@endunless
