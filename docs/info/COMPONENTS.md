# Componentes y vistas (`COMPONENTS.md`)

## Layouts (`resources/views/layouts/`)
- `app.blade.php` — layout principal público. Incluye enlace Skip to main content, `layouts.head` (con `@yield('head_css')`, `@yield('css')`, `@yield('head_js')`), `layouts.navbar`, `@yield('content')` dentro de `<main id="main-content">`, `layouts.footer`, `layouts.footer_meta`, `@yield('before_meta')` y `@yield('js')`.
- `head.blade.php` — metadatos SEO/OG/Twitter limpios (sin duplicados W3C), `@yield('structured_data')` JSON-LD, script de tema y carga de CSS.
- `navbar.blade.php` — navegación principal (`Home`, `About`, `Social` con estado `.active` / `aria-current="page"` dinámico y atributos ARIA en toggler), botón `Random` (`collections.random`) y formulario `Logout` si hay usuario autenticado.
- `footer.blade.php` — enlaces de autor (`raupulus.dev`, GitHub) y redes sociales del proyecto.
- `footer_meta.blade.php` — scripts y metadatos de cierre del layout principal.

## Componentes de galería (`resources/views/components/`)
- `_collection_block.blade.php` — tarjeta de una colección para el grid de portada y respuestas AJAX.
  - **Entrada esperada:** `$collection` (instancia de `App\Models\Collection`).
  - Muestra contador de imágenes, enlace a `$collection->url` en cabecera/título/botón hover, miniatura (`$image->urlThumbnail`) con `loading="lazy"` vinculada a `$image->urlImage` (`data-fancybox="gallery"`) para abrir el visor lightbox, y título resumido (`$collection->titleResume`).
- `_grid_images.blade.php` — parcial de rejilla de imágenes dentro de una colección.

## Vistas principales
- `resources/views/home.blade.php` — portada con buscador accesible (`<search>` + `<label>` + botón `Clear`), estado vacío (`@forelse`), grid inicial de 24 colecciones (`#box-all-collections-blocks`), contador inicializado desde servidor (`$collections->total()`) con `aria-live="polite"` y script de scroll infinito que consulta `collection.ajax.get.cards`.
- `resources/views/collections/show.blade.php` — detalle de colección con galería de imágenes, metadatos de generación en pestañas ARIA (`role="tablist"` / `role="tab"` / `role="tabpanel"`), diálogo nativo `<dialog>` para confirmaciones de borrado admin y región `aria-live` (`#admin-feedback-toast`).
- `resources/views/pages/show.blade.php` — contenedor de páginas estáticas que incluye `pages.content._about`, `_social`, `_cookies` o `_rgpd`.
- `resources/views/errors/404.blade.php` y `500.blade.php` — páginas de error con la identidad visual del sitio y retorno directo al inicio.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
