<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin') – {{ optional($settings)->site_title ?? __('messages.brand.name') }}</title>
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
                            primary: '{{ optional($settings)->admin_primary_color ?? "#1E3A5F" }}',
                            'primary-hover': '{{ optional($settings)->admin_primary_hover_color ?? "#2A4A75" }}',
                            secondary: '{{ optional($settings)->user_secondary_color ?? optional($settings)->secondary_color ?? "#E0F2FE" }}',
                            'secondary-hover': '{{ optional($settings)->user_secondary_hover_color ?? optional($settings)->secondary_hover_color ?? "#BAE6FD" }}',
                            accent: '{{ optional($settings)->accent_color ?? "#10B981" }}',
                            'accent-hover': '{{ optional($settings)->accent_hover_color ?? "#059669" }}',
                            neutral: '{{ optional($settings)->admin_background_color ?? "#F8FAFC" }}',
                        },
                    },
                },
            };
        </script>
    @endif

    {{-- Dynamic colors from settings (after CSS so they override) --}}
    <style>
        :root {
            --color-primary: {{ optional($settings)->admin_primary_color ?? '#1E3A5F' }};
            --color-primary-hover: {{ optional($settings)->admin_primary_hover_color ?? '#2A4A75' }};
            --color-secondary: {{ optional($settings)->user_secondary_color ?? optional($settings)->secondary_color ?? '#E0F2FE' }};
            --color-secondary-hover: {{ optional($settings)->user_secondary_hover_color ?? optional($settings)->secondary_hover_color ?? '#BAE6FD' }};
            --color-accent: {{ optional($settings)->accent_color ?? '#10B981' }};
            --color-accent-hover: {{ optional($settings)->accent_hover_color ?? '#059669' }};
            --color-neutral: {{ optional($settings)->admin_background_color ?? '#F8FAFC' }};
            --color-success: {{ optional($settings)->success_color ?? '#22C55E' }};
            --color-warning: {{ optional($settings)->warning_color ?? '#F59E0B' }};
            --color-error: {{ optional($settings)->error_color ?? '#EF4444' }};
            --color-info: {{ optional($settings)->info_color ?? '#3B82F6' }};
            --admin-bg: {{ optional($settings)->admin_background_color ?? '#F8FAFC' }};
            --admin-sidebar-bg: {{ optional($settings)->admin_sidebar_background_color ?? optional($settings)->admin_primary_color ?? '#1E3A5F' }};
            --admin-card-bg: {{ optional($settings)->admin_card_background_color ?? '#FFFFFF' }};
            --admin-border: {{ optional($settings)->admin_border_color ?? '#CBD5E1' }};
            --admin-text-primary: {{ optional($settings)->admin_text_primary_color ?? '#0F172A' }};
            --admin-text-secondary: {{ optional($settings)->admin_text_secondary_color ?? '#64748B' }};
            --admin-sidebar-active: {{ optional($settings)->admin_sidebar_active_color ?? '#10B981' }};
            --surface-card-bg: var(--admin-card-bg);
            --surface-card-border: var(--admin-border);
            --ui-button-bg: {{ optional($settings)->button_background_color ?? optional($settings)->accent_color ?? '#10B981' }};
            --ui-button-hover: {{ optional($settings)->button_hover_color ?? optional($settings)->accent_hover_color ?? '#059669' }};
            --ui-button-text: #FFFFFF;
        }

        body[data-scope="admin"] {
            background-color: var(--admin-bg);
            color: var(--admin-text-primary);
        }

        body[data-scope="admin"] .admin-sidebar {
            background-color: var(--admin-sidebar-bg);
            color: #fff;
        }

        body[data-scope="admin"] .admin-topbar {
            background-color: var(--admin-card-bg);
            border-color: var(--admin-border);
            color: var(--admin-text-primary);
        }

        body[data-scope="admin"] .admin-nav-active {
            color: var(--admin-sidebar-active) !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
</head>
<body data-scope="admin" class="antialiased bg-neutral text-slate-900">
    @php
        $adminLogoHeight = max(24, min(160, (int) (optional($settings)->logo_admin_height ?? 40)));
        $adminLogoMaxWidth = max(60, min(320, (int) (optional($settings)->logo_admin_max_width ?? 140)));
        $adminLogoAlignment = optional($settings)->logo_admin_alignment === 'left' ? 'justify-start' : 'justify-center';
    @endphp
    <div class="min-h-screen flex">
        <aside id="admin-sidebar" class="admin-sidebar fixed left-0 top-0 z-40 h-full w-16 md:w-56 bg-primary text-white flex flex-col transform transition-transform duration-200 ease-out translate-x-0" aria-hidden="false">
            <div class="p-2 md:p-4 border-b border-white/10 flex items-center justify-between min-h-[52px]">
                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2 {{ $adminLogoAlignment }}">
                    @if(optional($settings)->logo_admin)
                        @php $logoAdmin = $settings->logo_admin; @endphp
                        <img src="{{ (str_starts_with($logoAdmin, 'http://') || str_starts_with($logoAdmin, 'https://')) ? $logoAdmin : asset('storage/' . ltrim($logoAdmin, '/')) }}" alt="Admin" class="w-auto object-contain" style="height: {{ $adminLogoHeight }}px; max-width: {{ $adminLogoMaxWidth }}px;">
                    @else
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-accent flex items-center justify-center text-xs font-bold text-white">A</span>
                    @endif
                </a>
                <button type="button" id="admin-sidebar-close" class="md:hidden rounded-lg p-1.5 text-white/70 hover:text-white hover:bg-white/10 flex-shrink-0" aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-2 space-y-1 text-sm flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Dashboard">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/><path d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/><path d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="hidden md:inline">Dashboard</span>
                </a>
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.businesses*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Businesses">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="hidden md:inline">Businesses</span>
                </a>
                <a href="{{ route('admin.business-categories.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.business-categories*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Business Categories">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="hidden md:inline">Business Categories</span>
                </a>
                <a href="{{ route('admin.places.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.places*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Places to Visit">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="hidden md:inline">Places to Visit</span>
                </a>
                <a href="{{ route('admin.place-categories.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.place-categories*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Place Categories">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="hidden md:inline">Place Categories</span>
                </a>
                <a href="{{ route('admin.villages.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.villages*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Villages">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="hidden md:inline">Villages</span>
                </a>
                <a href="{{ route('admin.business-reviews.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.business-reviews*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Business Reviews">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.03 3.17a1 1 0 00.95.69h3.333c.969 0 1.371 1.24.588 1.81l-2.696 1.959a1 1 0 00-.364 1.118l1.03 3.17c.3.922-.755 1.688-1.539 1.118l-2.696-1.96a1 1 0 00-1.176 0l-2.696 1.96c-.784.57-1.838-.196-1.539-1.118l1.03-3.17a1 1 0 00-.364-1.118L2.235 8.597c-.783-.57-.38-1.81.588-1.81h3.333a1 1 0 00.95-.69l1.03-3.17z"/></svg>
                    <span class="hidden md:inline">Reviews</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.users*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Users">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="hidden md:inline">Users</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.subscriptions*') && !request()->routeIs('admin.subscription-plans*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Subscriptions">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="hidden md:inline">Subscriptions</span>
                </a>
                <a href="{{ route('admin.subscription-plans.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.subscription-plans*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Subscription plans">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="hidden md:inline">Plans</span>
                </a>
                <a href="{{ route('admin.media.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.media*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Media Library">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="hidden md:inline">Media</span>
                </a>
                <a href="{{ route('admin.statistics.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.statistics*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Visitor Statistics">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="hidden md:inline">Visitor Statistics</span>
                </a>
                <a href="{{ route('admin.page-texts.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.page-texts*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Page Texts">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h6" />
                    </svg>
                    <span class="hidden md:inline">Page Texts</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 hover:bg-white/10 {{ request()->routeIs('admin.settings*') ? 'bg-white/10 admin-nav-active' : 'text-white/90' }}" title="Settings">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="hidden md:inline">Settings</span>
                </a>
            </nav>
            <div class="p-2 border-t border-white/10">
                <button type="button" id="admin-sidebar-hide" class="hidden md:flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-white/70 hover:text-white hover:bg-white/10" title="Hide sidebar">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    <span>Hide menu</span>
                </button>
                <a href="{{ route('home') }}" class="flex items-center justify-center md:justify-start gap-2 rounded-lg px-2 md:px-3 py-2.5 text-white/70 hover:text-white hover:bg-white/10" title="View site">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span class="hidden md:inline text-sm">View site</span>
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
        <div id="admin-main-wrap" class="flex-1 flex flex-col min-w-0 transition-[margin] duration-200">
            <div class="admin-topbar sticky top-0 z-20 border-b px-4 py-3 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-primary">@yield('page_title', 'Admin')</h1>
            </div>
            <button type="button" id="admin-sidebar-open" class="fixed left-0 top-4 z-30 rounded-r-lg bg-primary text-white p-2 shadow-lg hover:bg-primary/90 transition hidden" aria-label="Open menu" title="Show menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <main class="flex-1 p-4 sm:p-8 overflow-auto">
                @if (session('success'))
                    <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
                @endif
                @if (session('error'))
                    <x-ui.alert variant="error" class="mb-4">{{ session('error') }}</x-ui.alert>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>
    (function() {
        var key = 'admin-sidebar-open';
        var sidebar = document.getElementById('admin-sidebar');
        var mainWrap = document.getElementById('admin-main-wrap');
        var btnOpen = document.getElementById('admin-sidebar-open');
        var btnHide = document.getElementById('admin-sidebar-hide');
        var btnClose = document.getElementById('admin-sidebar-close');
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
