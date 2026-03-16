<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', optional($settings)->meta_title ?? __('messages.meta.title_default'))</title>
    <meta name="description" content="@yield('meta_description', optional($settings)->meta_description ?? __('messages.meta.description_default'))">
    @if(!empty(optional($settings)->meta_keywords))
    <meta name="keywords" content="{{ $settings->meta_keywords }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $pageTitle = e(\Illuminate\Support\Str::limit(optional($settings)->meta_title ?? __('messages.meta.title_default'), 70));
        $pageDescription = e(\Illuminate\Support\Str::limit(optional($settings)->meta_description ?? __('messages.meta.description_default'), 200));
        $ogImage = optional($settings)->og_image;
        $ogImageUrl = $ogImage ? ((str_starts_with($ogImage, 'http://') || str_starts_with($ogImage, 'https://')) ? $ogImage : asset('storage/' . ltrim($ogImage, '/'))) : null;
        $ogSiteName = e(optional($settings)->og_site_name ?: (optional($settings)->site_title ?? __('messages.brand.name')));
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:site_name" content="{{ $ogSiteName }}">
    @if($ogImageUrl)
    <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif
    <meta name="twitter:card" content="{{ $ogImageUrl ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if($ogImageUrl)
    <meta name="twitter:image" content="{{ $ogImageUrl }}">
    @endif

    {{-- Favicon: always load local GTPR icon (with cache-busting) --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=3">
    <link rel="shortcut icon" href="/favicon.svg?v=3" type="image/svg+xml">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback styling when Vite is not running --}}
        <script src="https://cdn.tailwindcss.com?plugins=line-clamp"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '{{ optional($settings)->user_primary_color ?? optional($settings)->primary_color ?? "#1E3A5F" }}',
                            'primary-hover': '{{ optional($settings)->user_primary_hover_color ?? optional($settings)->primary_hover_color ?? "#2A4A75" }}',
                            secondary: '{{ optional($settings)->user_secondary_color ?? optional($settings)->secondary_color ?? "#E0F2FE" }}',
                            'secondary-hover': '{{ optional($settings)->user_secondary_hover_color ?? optional($settings)->secondary_hover_color ?? "#BAE6FD" }}',
                            accent: '{{ optional($settings)->accent_color ?? "#10B981" }}',
                            'accent-hover': '{{ optional($settings)->accent_hover_color ?? "#059669" }}',
                            neutral: '{{ optional($settings)->main_background_color ?? optional($settings)->neutral_color ?? "#F8FAFC" }}',
                        },
                        fontFamily: {
                            sans: ['system-ui', 'sans-serif'],
                        },
                    },
                },
            };
        </script>
    @endif

    {{-- Dynamic colors from settings (after CSS so they override) --}}
    <style>
        :root {
            --color-primary: {{ optional($settings)->user_primary_color ?? optional($settings)->primary_color ?? '#1E3A5F' }};
            --color-primary-hover: {{ optional($settings)->user_primary_hover_color ?? optional($settings)->primary_hover_color ?? '#2A4A75' }};
            --color-secondary: {{ optional($settings)->user_secondary_color ?? optional($settings)->secondary_color ?? '#E0F2FE' }};
            --color-secondary-hover: {{ optional($settings)->user_secondary_hover_color ?? optional($settings)->secondary_hover_color ?? '#BAE6FD' }};
            --color-accent: {{ optional($settings)->accent_color ?? '#10B981' }};
            --color-accent-hover: {{ optional($settings)->accent_hover_color ?? '#059669' }};
            --color-neutral: {{ optional($settings)->main_background_color ?? optional($settings)->neutral_color ?? '#F8FAFC' }};
            --color-success: {{ optional($settings)->success_color ?? '#22C55E' }};
            --color-warning: {{ optional($settings)->warning_color ?? '#F59E0B' }};
            --color-error: {{ optional($settings)->error_color ?? '#EF4444' }};
            --color-info: {{ optional($settings)->info_color ?? '#3B82F6' }};
            --app-button-bg: {{ optional($settings)->button_background_color ?? optional($settings)->accent_color ?? '#10B981' }};
            --app-button-hover: {{ optional($settings)->button_hover_color ?? optional($settings)->accent_hover_color ?? '#059669' }};
            --app-link-color: {{ optional($settings)->link_color ?? optional($settings)->accent_color ?? '#10B981' }};
            --app-link-hover: {{ optional($settings)->link_hover_color ?? optional($settings)->accent_hover_color ?? '#059669' }};
            --app-header-bg: {{ optional($settings)->header_background_color ?? optional($settings)->user_primary_color ?? '#1E3A5F' }};
            --app-header-text: {{ optional($settings)->header_text_color ?? '#FFFFFF' }};
            --app-nav-hover: {{ optional($settings)->nav_hover_color ?? optional($settings)->accent_color ?? '#10B981' }};
            --app-menu-bg: {{ optional($settings)->soft_background_color ?? optional($settings)->section_background_color ?? '#F1F5F9' }};
            --app-menu-text: {{ optional($settings)->text_primary_color ?? '#0F172A' }};
            --app-menu-muted: {{ optional($settings)->text_secondary_color ?? '#334155' }};
            --app-menu-border: {{ optional($settings)->border_light_color ?? optional($settings)->border_color ?? '#E2E8F0' }};
            --app-footer-bg: {{ optional($settings)->footer_background_color ?? optional($settings)->user_primary_color ?? '#1E3A5F' }};
            --app-footer-text: {{ optional($settings)->footer_text_color ?? '#FFFFFF' }};
            --app-body-bg: {{ optional($settings)->body_background_color ?? optional($settings)->main_background_color ?? '#F8FAFC' }};
            --app-section-bg: {{ optional($settings)->section_background_color ?? '#FFFFFF' }};
            --app-border: {{ optional($settings)->border_color ?? optional($settings)->border_light_color ?? '#CBD5E1' }};
            --app-text-primary: {{ optional($settings)->text_primary_color ?? '#0F172A' }};
            --app-text-secondary: {{ optional($settings)->text_secondary_color ?? '#334155' }};
            --app-text-muted: {{ optional($settings)->text_muted_color ?? '#64748B' }};
            --surface-card-bg: {{ optional($settings)->section_background_color ?? '#FFFFFF' }};
            --surface-card-border: {{ optional($settings)->border_light_color ?? optional($settings)->border_color ?? '#E2E8F0' }};
            --ui-button-bg: var(--app-button-bg);
            --ui-button-hover: var(--app-button-hover);
            --ui-button-text: #FFFFFF;
        }

        body[data-scope="app"] {
            background-color: var(--app-body-bg);
            color: var(--app-text-primary);
        }

        body[data-scope="app"] a {
            color: var(--app-link-color);
        }

        body[data-scope="app"] a:hover {
            color: var(--app-link-hover);
        }

        body[data-scope="app"] .site-menu-panel {
            background-color: var(--app-menu-bg);
            color: var(--app-menu-text);
            border-color: var(--app-menu-border);
            box-shadow: none;
        }

        body[data-scope="app"] .site-menu-panel a,
        body[data-scope="app"] .site-menu-panel button {
            color: var(--app-menu-text);
        }

        body[data-scope="app"] .site-menu-panel .site-menu-muted {
            color: var(--app-menu-muted);
        }

        body[data-scope="app"] #site-header .site-nav a,
        body[data-scope="app"] #site-header .site-nav a.nav-active,
        body[data-scope="app"] #site-header #account-dropdown-btn {
            color: var(--app-header-text);
        }
        body[data-scope="app"] #site-header .site-nav a:hover,
        body[data-scope="app"] #site-header .site-nav a.nav-active,
        body[data-scope="app"] #site-header #account-dropdown-btn:hover {
            color: var(--app-nav-hover);
        }

        body[data-scope="app"] .leaflet-container,
        body[data-scope="app"] .leaflet-pane,
        body[data-scope="app"] .leaflet-top,
        body[data-scope="app"] .leaflet-bottom {
            z-index: 1;
        }
    </style>
    @stack('head')
</head>
<body data-scope="app" class="antialiased bg-neutral text-slate-900 overflow-x-hidden">
    <div class="min-h-screen flex flex-col min-w-0">
        @include('partials.header')

        <main class="flex-1 min-w-0 w-full">
            @yield('hero')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>
    <script>
    document.querySelectorAll('.logout-form').forEach(function(form) {
        form.addEventListener('submit', function() {
            var btn = form.querySelector('.logout-btn');
            if (btn) { btn.disabled = true; }
        });
    });
    </script>
    @include('partials.cookie-consent')
</body>
</html>
