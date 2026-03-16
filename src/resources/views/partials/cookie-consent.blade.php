@if (! request()->cookies->has('gdpr_cookie_accepted'))
    <div
        id="gdpr-cookie-banner"
        class="fixed inset-x-0 bottom-0 z-40 bg-slate-900/95 text-white"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-sm leading-snug">
                <p class="font-medium">
                    {{ __('messages.cookie.message') }}
                </p>
                <p class="mt-1 text-slate-300">
                    <a href="{{ route('privacy') }}" class="underline underline-offset-2 hover:text-emerald-300">
                        {{ __('messages.cookie.more') }} ({{ __('messages.footer.privacy') }})
                    </a>
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button
                    id="gdpr-cookie-accept"
                    type="button"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-emerald-500 hover:bg-emerald-400 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 focus:ring-offset-slate-900"
                >
                    {{ __('messages.cookie.accept') }}
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function setCookie(name, value, days) {
                var expires = '';
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = '; expires=' + date.toUTCString();
                }
                document.cookie = name + '=' + (value || '') + expires + '; path=/';
            }

            function hasCookie(name) {
                return document.cookie.split(';').some(function (c) {
                    return c.trim().indexOf(name + '=') === 0;
                });
            }

            if (hasCookie('gdpr_cookie_accepted')) {
                return;
            }

            window.addEventListener('load', function () {
                var banner = document.getElementById('gdpr-cookie-banner');
                var button = document.getElementById('gdpr-cookie-accept');

                if (!banner || !button) {
                    return;
                }

                button.addEventListener('click', function () {
                    setCookie('gdpr_cookie_accepted', '1', 365);
                    banner.classList.add('hidden');
                });
            });
        })();
    </script>
@endif

