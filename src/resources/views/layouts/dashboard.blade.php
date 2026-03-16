<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', __('messages.dashboard.label')) – {{ optional($settings)->site_title ?? __('messages.brand.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#1e3a5f',
                            secondary: '#e0f2fe',
                            accent: '#10b981',
                            'accent-hover': '#059669',
                            neutral: '#f8fafc',
                        },
                    },
                },
            };
        </script>
    @endif
</head>
<body class="antialiased bg-neutral text-slate-900">
    @php
        $frontendPanelLogoHeight = max(24, min(220, (int) (optional($settings)->logo_frontend_height ?? 80)));
        $frontendPanelLogoMaxWidth = max(60, min(480, (int) (optional($settings)->logo_frontend_max_width ?? 260)));
        $frontendPanelLogoAlignment = optional($settings)->logo_frontend_alignment === 'center' ? 'justify-center' : 'justify-start';
    @endphp
    <div class="min-h-screen flex">
        <aside id="dashboard-sidebar" class="fixed left-0 top-0 z-40 h-full w-16 md:w-56 bg-primary text-white flex flex-col transform transition-transform duration-200 ease-out translate-x-0" aria-hidden="false">
            <div class="p-2 md:p-4 border-b border-white/10 flex items-center justify-between min-h-[52px]">
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 min-w-0 {{ $frontendPanelLogoAlignment }}">
                    @if(optional($settings)->logo_frontend)
                        @php $logo = $settings->logo_frontend; @endphp
                        <img src="{{ (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) ? $logo : asset('storage/' . ltrim($logo, '/')) }}" alt="{{ optional($settings)->site_title ?? __('messages.brand.name') }}" class="w-auto object-contain" style="height: {{ min($frontendPanelLogoHeight, 80) }}px; max-width: {{ min($frontendPanelLogoMaxWidth, 220) }}px;">
                    @else
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-accent flex items-center justify-center text-xs font-bold text-white">D</span>
                    @endif
                </a>
                <button type="button" id="dashboard-sidebar-close" class="md:hidden rounded-lg p-1.5 text-white/70 hover:text-white hover:bg-white/10 flex-shrink-0" aria-label="{{ __('messages.dashboard.close_menu') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-2 space-y-1 text-sm flex-1 overflow-y-auto">
                <a href="{{ route('dashboard.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('dashboard.index') ? 'bg-white/10 text-accent' : 'text-white/90' }}" title="{{ __('messages.dashboard.my_business') }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="hidden md:inline">{{ __('messages.dashboard.my_business') }}</span>
                </a>
                <a href="{{ route('dashboard.business.reviews.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('dashboard.business.reviews.*') ? 'bg-white/10 text-accent' : 'text-white/90' }}" title="{{ __('messages.reviews.title') }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.03 3.17a1 1 0 00.95.69h3.333c.969 0 1.371 1.24.588 1.81l-2.696 1.959a1 1 0 00-.364 1.118l1.03 3.17c.3.922-.755 1.688-1.539 1.118l-2.696-1.96a1 1 0 00-1.176 0l-2.696 1.96c-.784.57-1.838-.196-1.539-1.118l1.03-3.17a1 1 0 00-.364-1.118L2.235 8.597c-.783-.57-.38-1.81.588-1.81h3.333a1 1 0 00.95-.69l1.03-3.17z"/></svg>
                    <span class="hidden md:inline">{{ __('messages.reviews.title') }}</span>
                </a>
                <a href="{{ route('dashboard.subscription') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('dashboard.subscription') ? 'bg-white/10 text-accent' : 'text-white/90' }}" title="{{ __('messages.subscription.status') }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="hidden md:inline">{{ __('messages.subscription.status') }}</span>
                </a>
            </nav>
            <div class="p-2 border-t border-white/10">
                <button type="button" id="dashboard-sidebar-hide" class="hidden md:flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-white/70 hover:text-white hover:bg-white/10" title="{{ __('messages.dashboard.hide_sidebar') }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    <span>{{ __('messages.dashboard.hide_menu') }}</span>
                </button>
                <a href="{{ route('home') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 text-white/70 hover:text-white hover:bg-white/10" title="{{ __('messages.dashboard.view_site') }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span class="hidden md:inline text-sm">{{ __('messages.dashboard.view_site') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1 logout-form">
                    @csrf
                    <button type="submit" class="logout-btn flex items-center justify-center md:justify-start gap-2 w-full rounded-lg px-2 md:px-3 py-2.5 text-white/70 hover:text-white hover:bg-white/10 disabled:opacity-70 disabled:pointer-events-none" title="{{ __('messages.auth.logout') }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="hidden md:inline text-sm">{{ __('messages.auth.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>
        <div id="dashboard-main-wrap" class="flex-1 flex flex-col min-w-0 transition-[margin] duration-200">
            <button type="button" id="dashboard-sidebar-open" class="fixed left-0 top-4 z-30 rounded-r-lg bg-primary text-white p-2 shadow-lg hover:bg-primary/90 transition hidden" aria-label="{{ __('messages.dashboard.open_menu') }}" title="{{ __('messages.dashboard.show_menu') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        <main class="flex-1 p-4 sm:p-8 overflow-auto">
            @if (session('success'))
                <p class="mb-4 text-sm text-accent">{{ session('success') }}</p>
            @endif
            @if (session('error'))
                <p class="mb-4 text-sm text-red-600">{{ session('error') }}</p>
            @endif
            @if (session('info'))
                <p class="mb-4 text-sm text-sky-600">{{ session('info') }}</p>
            @endif
            @yield('content')
        </main>
        </div>
    </div>
    <script>
    (function() {
        var key = 'dashboard-sidebar-open';
        var sidebar = document.getElementById('dashboard-sidebar');
        var mainWrap = document.getElementById('dashboard-main-wrap');
        var btnOpen = document.getElementById('dashboard-sidebar-open');
        var btnHide = document.getElementById('dashboard-sidebar-hide');
        var btnClose = document.getElementById('dashboard-sidebar-close');
        function isOpen() {
            var saved = localStorage.getItem(key);
            if (saved !== null) return saved !== 'false';
            return window.matchMedia('(min-width: 768px)').matches;
        }
        function setOpen(open) {
            localStorage.setItem(key, open ? 'true' : 'false');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full', !open);
                sidebar.classList.toggle('translate-x-0', open);
                sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
            }
            if (mainWrap) { mainWrap.classList.toggle('ml-16', open); mainWrap.classList.toggle('md:ml-56', open); }
            if (btnOpen) btnOpen.classList.toggle('hidden', open);
        }
        if (sidebar && mainWrap) {
            setOpen(isOpen());
            if (btnHide) btnHide.addEventListener('click', function() { setOpen(false); });
            if (btnOpen) btnOpen.addEventListener('click', function() { setOpen(true); });
            if (btnClose) btnClose.addEventListener('click', function() { setOpen(false); });
        }
    })();
    document.querySelectorAll('.logout-form').forEach(function(form) {
        form.addEventListener('submit', function() {
            var btn = form.querySelector('.logout-btn');
            if (btn) { btn.disabled = true; btn.classList.add('opacity-70'); }
        });
    });
    </script>
</body>
</html>
