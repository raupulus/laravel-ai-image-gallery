# Diseño y estilos (`DESIGN.md`)

## Arquitectura de estilos
El frontend público utiliza **Bootstrap 5** empaquetado como tema estático en `public/theme/`, complementado con hojas CSS personalizadas en `public/css/` y **Fancybox** para la vista ampliada de imágenes.

Orden de carga base en `resources/views/layouts/head.blade.php`:
1. Preload de la fuente crítica `Aclonica` (`public/theme/fonts/K2FyfZJVlfNNSEBXGY7UAo-KBSLy.woff2`, `crossorigin`).
2. `public/theme/bootstrap/css/bootstrap.min.css`
3. `public/theme/css/styles.min.css` (incluye `@font-face`, `.bs-icon*` y únicamente la animación `.flash` usada en navbar; el resto de `animate.css` fue purgado).
4. `public/css/styles.css`

Estilos y scripts específicos por vista (inyectados mediante `@section('css')` / `@section('js')`):
- `public/css/collection_blocks.css` (solo portada `/`)
- `public/theme/css/fancybox.css` y `public/theme/js/fancybox.umd.js` (`defer`) — solo en portada `/` y detalle `/collections/show/{collection}`
- `public/css/collection_show.css` (detalle de colección)
- `public/css/pages/*` (páginas estáticas)

## Tema y contraste
- El tema base **Bootswatch Superhero** (`public/theme/bootstrap/css/bootstrap.min.css`) define su paleta oscura nativa bajo `:root, [data-bs-theme=light]`, por lo que `resources/views/layouts/app.blade.php` fija `<html data-bs-theme="light">` para mantener coherencia visual y evitar la regresión de contraste por sobrescritura con `[data-bs-theme=dark]` genérico de Bootstrap.
- `public/css/styles.css` define tokens CSS globales (`--gallery-bg`, `--gallery-surface`, `--gallery-text`, `--gallery-muted`, `--gallery-accent`, `--gallery-accent-strong`) con ratios WCAG AA (≥ 4.5:1), estilos `:focus-visible` y el diseño del formulario de `/login`.
- Las rejillas y controles interactivos (`public/css/pages/show.css`, `collection_blocks.css`, `collection_show.css`) usan `grid-template-columns: repeat(auto-fit, minmax(min(100%, ...), 1fr))` sin desbordamiento horizontal y garantizan áreas táctiles mínimas de `44×44px` (WCAG 2.5.8).
- La barra de navegación (`resources/views/layouts/navbar.blade.php`) y el pie (`resources/views/layouts/footer.blade.php`) mantienen fondo oscuro (`bg-dark`).

## Paginación
- `App\Providers\AppServiceProvider::boot()` configura `Paginator::defaultView('pagination::bootstrap-5')`.

## SEO y metadatos
- `resources/views/layouts/head.blade.php` centraliza `<title>`, `canonical`, `meta-description`, OpenGraph (`og:*`, `og:locale` `en_US`), Twitter Cards (`twitter:*`), favicons `.ico` (`32x32`, `96x96`, `256x256`), `robots` (`noindex, nofollow` en `/login` y búsquedas `?search=`) y `@yield('structured_data')` (JSON-LD `WebSite` en portada e `ImageGallery` en detalle), sobrescribibles desde cada vista.
- `public/robots.txt` bloquea `/collections/ajax/`, `/webhooks/` y `/login` y declara `Sitemap: https://aidyslexic.raupulus.dev/sitemap.xml`.
- Google Analytics (`gtag.js`) se carga en `resources/views/layouts/app.blade.php` únicamente cuando `config('services.google.analytics')` (`GOOGLE_ANALYTICS`) está definido **y** el usuario otorga consentimiento explícito (`localStorage.cookie_consent === 'accepted'`) mediante el banner `#cookie-consent-banner`.

## Assets estáticos y caching HTTP
- No se usa Vite ni bundler JS en producción: todo el CSS/JS se sirve directamente desde `public/theme/`, `public/css/` (con busting `?v=filemtime(...)`) y `public/images/`.
- Los scripts JS globales del tema (`bootstrap.min.js`, `script.min.js`) se cargan desde `public/theme/` con `defer` en `footer_meta.blade.php`.
- `public/.htaccess` habilita compresión (`mod_deflate`) y cabeceras `Expires` (`mod_expires`) en Apache; en despliegues con Nginx es necesario configurar `Cache-Control` largo directamente en el bloque `server` (ver `docs/deploys/README.md`).

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
