<footer class="overflow-hidden border-t" style="background-color: var(--app-footer-bg); color: var(--app-footer-text); border-color: var(--app-border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="grid gap-10 md:grid-cols-4 min-w-0">
            <div class="space-y-4 md:col-span-2">
                <div class="flex items-center">
                    @php
                        $footerLogo = optional($settings)->logo_footer ?: optional($settings)->logo_frontend;
                        $footerHeight = max(20, min(120, (int) (optional($settings)->logo_footer_height ?? optional($settings)->logo_frontend_height ?? 40)));
                        $footerMaxWidth = max(60, min(280, (int) (optional($settings)->logo_footer_max_width ?? optional($settings)->logo_frontend_max_width ?? 160)));
                    @endphp
                    @if($footerLogo)
                        <a href="{{ route('home') }}" class="inline-flex shrink-0">
                            <img src="{{ (str_starts_with($footerLogo, 'http://') || str_starts_with($footerLogo, 'https://')) ? $footerLogo : asset('storage/' . ltrim($footerLogo, '/')) }}" alt="{{ optional($settings)->site_title ?? __('messages.brand.name') }}" class="w-auto object-contain opacity-90 transition-opacity hover:opacity-100" style="height: {{ $footerHeight }}px; max-width: {{ $footerMaxWidth }}px;">
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="flex shrink-0 items-center justify-center rounded-full bg-accent text-white text-xs font-semibold transition-opacity hover:opacity-90" style="height: {{ $footerHeight }}px; width: {{ $footerHeight }}px;">
                            {{ strtoupper(substr(optional($settings)->site_title ?? __('messages.brand.name'), 0, 2)) }}
                        </a>
                    @endif
                </div>
                <div class="prose prose-sm max-w-none" style="color: color-mix(in srgb, var(--app-footer-text) 85%, transparent);">
                    {!! nl2br(e(app()->getLocale() === 'el' ? __('messages.footer.about_text') : (optional($settings)->footer_content ?: __('messages.footer.about_text')))) !!}
                </div>
            </div>

            <div>
                <h3 class="mb-4 text-sm font-semibold" style="color: var(--app-footer-text);">{{ __('messages.footer.explore') }}</h3>
                <ul class="space-y-2 text-sm" style="color: color-mix(in srgb, var(--app-footer-text) 80%, transparent);">
                    <li><a href="{{ route('places.index') }}" class="hover:text-accent transition">{{ __('messages.nav.places_to_visit') }}</a></li>
                    <li><a href="{{ route('businesses') }}" class="hover:text-accent transition">{{ __('messages.nav.businesses') }}</a></li>
                    <li><a href="{{ route('businesses.on_map') }}" class="hover:text-accent transition">{{ __('messages.nav.on_map') }}</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-accent transition">{{ __('messages.nav.about') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="mb-4 text-sm font-semibold" style="color: var(--app-footer-text);">{{ __('messages.footer.connect') }}</h3>
                <ul class="space-y-2 text-sm" style="color: color-mix(in srgb, var(--app-footer-text) 80%, transparent);">
                    <li><a href="{{ route('contact') }}" class="hover:text-accent transition">{{ __('messages.nav.contact') }}</a></li>
                    @foreach(optional($settings)->social_links ?? [] as $network => $url)
                        @if($url && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')))
                            <li><a href="{{ $url }}" target="_blank" rel="noopener" class="hover:text-accent transition">{{ ucfirst($network) }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between border-t pt-8 text-xs sm:flex-row" style="border-color: color-mix(in srgb, var(--app-footer-text) 20%, transparent); color: color-mix(in srgb, var(--app-footer-text) 70%, transparent);">
            <p>&copy; {{ now()->year }} {{ optional($settings)->site_title ?? __('messages.brand.name') }}. {{ __('messages.footer.rights') }}</p>
            <div class="mt-2 sm:mt-0 flex items-center space-x-4">
                <a href="{{ route('privacy') }}" class="hover:text-accent transition">{{ __('messages.footer.privacy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-accent transition">{{ __('messages.footer.terms') }}</a>
            </div>
        </div>
    </div>
</footer>
