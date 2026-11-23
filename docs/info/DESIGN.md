# Diseño y estilos (`DESIGN.md`)

## Arquitectura de estilos
El frontend público utiliza **Bootstrap 5** empaquetado como tema estático en `public/theme/`, complementado con hojas CSS personalizadas en `public/css/` y **Fancybox** para la vista ampliada de imágenes.

Orden de carga en `resources/views/layouts/head.blade.php`:
1. `public/theme/bootstrap/css/bootstrap.min.css`
2. `public/theme/css/styles.min.css`
3. `public/css/styles.css`
4. `public/css/collection_blocks.css`
5. `public/theme/css/fancybox.css`

Estilos específicos por vista (inyectados mediante `@section('head_css')` o `@section('css')`):
- `public/css/collection_show.css` (detalle de colección)
- `public/css/pages/*` (páginas estáticas)

## Tema claro / oscuro
- El elemento `<html>` en `resources/views/layouts/app.blade.php` declara `data-bs-theme="auto"`.
- Un script inline en `resources/views/layouts/head.blade.php` gestiona la preferencia de tema:
  - Prioridad: `data-bss-forced-theme` > `localStorage.getItem('theme')` > `data-bs-theme` > `prefers-color-scheme: dark`.
  - Actualiza `data-bs-theme` en `<html>` y escucha cambios de `prefers-color-scheme` y clics en botones `[data-bs-theme-value]`.
- La barra de navegación (`resources/views/layouts/navbar.blade.php`) y el pie (`resources/views/layouts/footer.blade.php`) usan fondo oscuro fijo (`bg-dark`, `data-bs-theme="dark"` en navbar).

## Paginación
- `App\Providers\AppServiceProvider::boot()` configura `Paginator::defaultView('pagination::bootstrap-5')`.

## SEO y metadatos
- `resources/views/layouts/head.blade.php` centraliza `<title>`, `canonical`, `meta-description`, OpenGraph (`og:*`, `og:locale` `en_US`), Twitter Cards (`twitter:*`), favicons `.ico` (`32x32`, `96x96`, `256x256`), `robots` (`noindex, nofollow` en `/login` y búsquedas `?search=`) y `@yield('structured_data')` (JSON-LD `WebSite` en portada e `ImageGallery` en detalle), sobrescribibles desde cada vista.
- `public/robots.txt` bloquea `/collections/ajax/`, `/webhooks/` y `/login` y declara `Sitemap: https://aidyslexic.raupulus.dev/sitemap.xml`.
- Google Analytics (`gtag.js`) se carga en `resources/views/layouts/app.blade.php` únicamente cuando `config('services.google.analytics')` (`GOOGLE_ANALYTICS`) está definido **y** el usuario otorga consentimiento explícito (`localStorage.cookie_consent === 'accepted'`) mediante el banner `#cookie-consent-banner`.

## Assets con Vite
- `vite.config.js` compila `resources/css/app.css` (actualmente vacío) y `resources/js/app.js` (importa `./bootstrap` con `axios`/`lodash`).
- Los scripts JS del tema se sirven directamente desde `public/theme/js/` (`fancybox.umd.js`, `script.min.js`).

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
