<meta charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

{{-- Título, 50-60 carácteres máximo --}}
<title>@yield('title', config('app.name'))</title>

<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="csrf_token" content="{{ csrf_token() }}" />

{{-- Idioma del contenido --}}
<meta property="og:locale" content="@yield('meta-og-locale', 'en_EN')">
<meta http-equiv="Content-Language" content="en" />

{{-- Alcance para distribuir el contenido --}}
<meta name="distribution" content="global" />

@yield('meta')

<meta name="language" content="@yield('meta-og-locale', 'English')">
<meta name="description" content="@yield('meta-description', config('app.description'))" /> {{-- Máximo 150-160 carácteres --}}
<meta name="author" content="@yield('meta-author', 'Raúl Caro Pastorino')" />
<meta name="copyright" content="@yield('meta-author', 'Raúl Caro Pastorino')" />
<meta name="robots" content="@yield('meta-robots', 'index, follow')" />
<meta name="keywords" content="@yield('meta-keywords', 'AI, Stable Diffusion, IA, Inteligencia artificial, raupulus, Raúl Caro Pastorino')" />

<meta name="url" content="{{request()->fullUrl()}}">
<meta name="identifier-URL" content="{{request()->fullUrl()}}">
<meta name="revisit-after" content="7 days">


{{-- Redes sociales --}}
<meta property="og:title"
      content="@yield('meta-og-title', config('app.name'))" />
<meta property="og:site_name"
      content="@yield('meta-og-site_name', config('app.name'))" />
<meta property="og:type" content="@yield('og-type', 'website')" />
<meta property="og:description"
      content="@yield('meta-og-description', config('app.description'))" />
<meta property="og:image"
      content="@yield('meta-og-image', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:image:url"
      content="@yield('meta-og-image-url', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:image:secure_url"
      content="@yield('meta-og-image-secure_url', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:url"
      content="@yield('meta-og-url', request()->fullUrl())" />
<meta property="og:image:alt"
      content="@yield('meta-og-image_alt', config('app.description'))" />
<meta property="og:image:type"
      content="@yield('meta-og-image-type', 'image/webp')" />

<meta name="twitter:title" content="@yield('meta-twitter-title', config('app.name'))" />
<meta name="twitter:card" content="@yield('meta-twitter-card', 'summary')" />
<meta name="twitter:site" content="@yield('meta-twitter-site', '@raupulus')" />
<meta name="twitter:creator" content="@yield('meta-twitter-creator', '@raupulus')" />

{{-- Iconos --}}
{{-- TODO → Dinamizar para reescribirlo desde cada parte de la api --}}
<link rel="apple-touch-icon" sizes="32x32" href="{{asset('images/favicons/favicon_32x32.ico')}}" />
<link rel="apple-touch-icon" sizes="256x256" href="{{asset('images/favicons/favicon_256x256.ico')}}" />
<link rel="icon" type="image/png" sizes="256x256"  href="{{asset('images/favicons/favicon_256x256.ico')}}" />
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicons/favicon_32x32.ico')}}" />
<link rel="icon" type="image/png" sizes="96x96" href="{{asset('images/favicons/favicon_96x96.png')}}" />


<script>
    (function() {

        // JavaScript snippet handling Dark/Light mode switching

        const getStoredTheme = () => localStorage.getItem('theme');
        const setStoredTheme = theme => localStorage.setItem('theme', theme);
        const forcedTheme = document.documentElement.getAttribute('data-bss-forced-theme');

        const getPreferredTheme = () => {

            if (forcedTheme) return forcedTheme;

            const storedTheme = getStoredTheme();
            if (storedTheme) {
                return storedTheme;
            }

            const pageTheme = document.documentElement.getAttribute('data-bs-theme');

            if (pageTheme) {
                return pageTheme;
            }

            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        const setTheme = theme => {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
        }

        setTheme(getPreferredTheme());

        const showActiveTheme = (theme, focus = false) => {
            const themeSwitchers = [].slice.call(document.querySelectorAll('.theme-switcher'));

            if (!themeSwitchers.length) return;

            document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                element.classList.remove('active');
                element.setAttribute('aria-pressed', 'false');
            });

            for (const themeSwitcher of themeSwitchers) {

                const btnToActivate = themeSwitcher.querySelector('[data-bs-theme-value="' + theme + '"]');

                if (btnToActivate) {
                    btnToActivate.classList.add('active');
                    btnToActivate.setAttribute('aria-pressed', 'true');
                }
            }
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            showActiveTheme(getPreferredTheme());

            document.querySelectorAll('[data-bs-theme-value]')
                .forEach(toggle => {
                    toggle.addEventListener('click', (e) => {
                        e.preventDefault();
                        const theme = toggle.getAttribute('data-bs-theme-value');
                        setStoredTheme(theme);
                        setTheme(theme);
                        showActiveTheme(theme);
                    })
                })
        });
    })();
</script>
<link rel="stylesheet" href="{{asset('theme/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/css/styles.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/css/fancybox.css')}}">
