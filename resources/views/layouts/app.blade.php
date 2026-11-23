<!DOCTYPE html>
<!--
@author Raúl Caro Pastorino
@copyright Copyright (c) 2023 Raúl Caro Pastorino
@license https://www.gnu.org/licenses/gpl-3.0-standalone.html

Author Web: https://raupulus.dev
E-mail: public@raupulus.dev
-->

<html data-bs-theme="auto" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.head')
    @yield('head_css')
    @yield('head_js')

    @if(config('services.google.analytics'))
        <script>
            (function () {
                const gaId = @json(config('services.google.analytics'));

                function loadGoogleAnalytics() {
                    if (window.__gaLoaded || !gaId) {
                        return;
                    }
                    window.__gaLoaded = true;
                    const script = document.createElement('script');
                    script.async = true;
                    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
                    document.head.appendChild(script);

                    window.dataLayer = window.dataLayer || [];
                    window.gtag = function () {
                        window.dataLayer.push(arguments);
                    };
                    window.gtag('js', new Date());
                    window.gtag('config', gaId);
                }

                window.setCookieConsent = function (choice) {
                    try {
                        localStorage.setItem('cookie_consent', choice);
                    } catch (e) {}

                    const banner = document.getElementById('cookie-consent-banner');
                    if (banner) {
                        banner.classList.add('d-none');
                    }

                    if (choice === 'accepted') {
                        loadGoogleAnalytics();
                    }
                };

                window.resetCookieConsent = function () {
                    try {
                        localStorage.removeItem('cookie_consent');
                    } catch (e) {}

                    const banner = document.getElementById('cookie-consent-banner');
                    if (banner) {
                        banner.classList.remove('d-none');
                    }
                };

                let storedConsent = null;
                try {
                    storedConsent = localStorage.getItem('cookie_consent');
                } catch (e) {}

                if (storedConsent === 'accepted') {
                    loadGoogleAnalytics();
                } else if (!storedConsent) {
                    window.addEventListener('DOMContentLoaded', function () {
                        const banner = document.getElementById('cookie-consent-banner');
                        if (banner) {
                            banner.classList.remove('d-none');
                        }
                    });
                }
            })();
        </script>
    @endif
</head>

<body class="text-info" style="height: 100%;min-height: 100vh;">
@include('layouts.navbar')

<div id="app">
    @yield('content')
</div>

@include('layouts.footer')
@if(config('services.google.analytics'))
    <div id="cookie-consent-banner"
         role="dialog"
         aria-label="Cookie consent"
         class="d-none position-fixed bottom-0 start-0 end-0 bg-dark text-white border-top border-secondary p-3 shadow-lg"
         style="z-index: 1080;">
        <div class="container-xl d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <p class="mb-0 small">
                We use strictly necessary cookies and, with your consent, optional analytics cookies to improve the site.
                Read our <a href="{{ route('page.show', 'cookies') }}" class="link-light text-decoration-underline">Cookies Policy</a>
                and <a href="{{ route('page.show', 'rgpd') }}" class="link-light text-decoration-underline">Privacy &amp; GDPR</a>.
            </p>
            <div class="d-flex flex-shrink-0 gap-2">
                <button type="button" class="btn btn-outline-light btn-sm" onclick="window.setCookieConsent('rejected')">
                    Reject
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.setCookieConsent('accepted')">
                    Accept
                </button>
            </div>
        </div>
    </div>
@endif
@yield('before_meta')
@include('layouts.footer_meta')
@yield('css')
@yield('js')
</body>
</html>
