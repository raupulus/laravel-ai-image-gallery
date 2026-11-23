# Componentes y vistas (`COMPONENTS.md`)

## Layouts (`resources/views/layouts/`)
- `app.blade.php` — layout principal público. Incluye enlace Skip to main content, `layouts.head` (con `@yield('head_css')`, `@yield('css')`, `@yield('head_js')`), `layouts.navbar`, `@yield('content')` dentro de `<main id="main-content">`, `layouts.footer`, `layouts.footer_meta`, `@yield('before_meta')` y `@yield('js')`.
- `guest.blade.php` — layout de Laravel Breeze para vistas de autenticación (`App\View\Components\GuestLayout`).
- `head.blade.php` — metadatos SEO/OG/Twitter limpios (sin duplicados W3C), `@yield('structured_data')` JSON-LD, script de tema y carga de CSS.
- `navbar.blade.php` — navegación principal (`Home`, `About`, `Social` con estado `.active` / `aria-current="page"` dinámico y atributos ARIA en toggler), botón `Random` (`collections.random`) y formulario `Logout` si hay usuario autenticado.
- `footer.blade.php` — enlaces de autor (`raupulus.dev`, GitHub) y redes sociales del proyecto.
- `footer_meta.blade.php` — scripts y metadatos de cierre del layout principal.

## Componentes de galería (`resources/views/components/`)
- `_collection_block.blade.php` — tarjeta de una colección para el grid de portada y respuestas AJAX.
  - **Entrada esperada:** `$collection` (instancia de `App\Models\Collection`).
  - Muestra contador de imágenes, enlace a `$collection->url`, miniatura (`$image->urlThumbnail`) con enlace a imagen completa (`$image->urlImage`) y atributo `data-fancybox="gallery"`, y título resumido (`$collection->titleResume`).
- `_grid_collections.blade.php` — parcial de rejilla de colecciones.
- `_grid_images.blade.php` — parcial de rejilla de imágenes dentro de una colección.

## Componentes Breeze (`resources/views/components/`)
- `application-logo.blade.php`, `auth-session-status.blade.php`, `danger-button.blade.php`, `dropdown.blade.php`, `dropdown-link.blade.php`, `input-error.blade.php`, `input-label.blade.php`, `modal.blade.php`, `nav-link.blade.php`, `primary-button.blade.php`, `responsive-nav-link.blade.php`, `secondary-button.blade.php`, `text-input.blade.php`.
- Utilizados por las vistas de `resources/views/auth/`.

## Vistas principales
- `resources/views/home.blade.php` — portada con buscador (`GET /?search=...`), grid inicial de 24 colecciones (`#box-all-collections-blocks`) y script inline de scroll infinito que consulta `collection.ajax.get.cards`.
- `resources/views/collections/show.blade.php` — detalle de colección con galería de imágenes, metadatos de generación (prompt, modelo, parámetros) y controles de borrado para admin.
- `resources/views/pages/show.blade.php` — contenedor de páginas estáticas que incluye `pages.content._about`, `_social`, `_cookies` o `_rgpd`.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
