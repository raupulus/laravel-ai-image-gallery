# Módulo: `pages` (Páginas estáticas)

## Qué hace y qué NO hace
- **Hace:**
  - Sirve páginas informativas estáticas definidas en configuración interna de `App\Http\Controllers\PageController` bajo `/page/{page}`.
  - Expone la lista de páginas activas (`PageController::getActivePages()`) para que el generador de sitemap indexe solo las activas.
- **NO hace:**
  - No almacena páginas en base de datos ni dispone de CMS o panel de edición.

## Modelo de datos
- Sin tablas en BD. Las páginas se definen en `PageController::$pages`:
  - `about` (`pages.content._about`, `active: true`)
  - `social` (`pages.content._social`, `active: true`)
  - `cookies` (`pages.content._cookies`, `active: true`)
  - `rgpd` (`pages.content._rgpd`, `active: true`)

## Flujos principales
1. **Visualización (`PageController@show`):**
   - Busca `$page` en `self::$pages`.
   - Si no existe, redirige a `route('home')`.
   - Si existe (incluso si `active` es `false`), renderiza `resources/views/pages/show.blade.php` incluyendo el parcial correspondiente y pasando el array `$socials` con texto plano escapado con `nl2br(e(...))`, `rel="noopener noreferrer"` y atributos ARIA en los enlaces a repositorios y redes sociales.

## Puntos de entrada
| Método | Ruta | Nombre | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- | --- |
| `GET` | `/page/{page}` | `page.show` | Pública | Redirige a `home` si el slug no existe |

## Dependencias
- **Depende de:** vistas en `resources/views/pages/` (`show.blade.php`, `content/_about.blade.php`, `_cookies.blade.php`, `_rgpd.blade.php`, `_social.blade.php`).
- **Es usado por:** `sitemap` (`GenerateSitemap` llama a `PageController::getActivePages()`) y `layouts/navbar.blade.php`.

## Configuración
- No utiliza variables de entorno propias.

## Trampas conocidas
- Si en el futuro se marca una página con `active => false`, solo se excluye de `PageController::getActivePages()` (y del sitemap), pero seguirá respondiendo HTTP `200` por URL directa mientras permanezca en `$pages`.

## Tests que lo cubren
- `tests/Feature/CollectionTest.php::test_static_pages_render_or_redirect` (`/page/about`, `/page/cookies`, `/page/invalid-page`).
- `tests/Feature/SitemapCommandTest.php::test_sitemap_excludes_empty_pages`.

## Pendiente real
- Ninguno verificado en código.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
