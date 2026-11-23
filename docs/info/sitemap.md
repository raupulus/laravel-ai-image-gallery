# Módulo: `sitemap` (Generación de `sitemap.xml` y scheduler)

## Qué hace y qué NO hace
- **Hace:**
  - Genera un archivo `sitemap.xml` válido según el protocolo Sitemap 0.9 y la extensión Google Image Sitemap 1.1 (`xmlns:image`).
  - Incluye:
    1. La portada (`route('home')`, `priority: 1.0`, `changefreq: daily`, `lastmod` de la última colección válida).
    2. Las páginas estáticas activas (`PageController::getActivePages()`, `priority: 0.6`, `changefreq: monthly`).
    3. Las colecciones no vacías con título válido y al menos una imagen (`priority: 0.8`, `changefreq: weekly`), incluyendo etiquetas `<image:image>` con `image:loc`, `image:title` e `image:caption`.
  - Se ejecuta automáticamente todos los días a las `03:42` mediante el scheduler de Laravel (`app/Console/Kernel.php`).
- **NO hace:**
  - No incluye colecciones sin imágenes ni con título vacío o `null`.
  - No incluye páginas con `active => false` (`cookies`, `rgpd`).

## Modelo de datos
- Solo lectura sobre `collections` e `images` (ver [`collections.md`](./collections.md)).

## Flujos principales
1. Ejecución manual (`php artisan sitemap:generate`) o programada (`php artisan schedule:run` a las `03:42`).
2. `GenerateSitemap::handle()` construye el XML en memoria con `XMLWriter`, crea el directorio destino si no existe y escribe el archivo en `public/sitemap.xml` (o en la ruta indicada con `--path=`).

## Puntos de entrada
| Tipo | Firma | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- |
| Comando Artisan | `sitemap:generate {--path=} {--force}` | CLI | `--path` sobrescribe `public/sitemap.xml`; `--force` aceptado por compatibilidad CI/CD |
| Scheduler | `Kernel::schedule()` | Cron del servidor | Expresión `42 3 * * *` (`dailyAt('03:42')`) |

## Dependencias
- **Depende de:** `collections` (`Collection`, `Image`), `pages` (`PageController::getActivePages()`), extensión PHP `XMLWriter`, `APP_URL` correcto para generar URLs absolutas.
- **Es usado por:** despliegue y cron de producción (`public/sitemap.xml`).

## Configuración
| Variable / Clave | Valor por defecto | Efecto |
| --- | --- | --- |
| `APP_URL` | `http://localhost` (`.env.example`) | Determina el dominio de todas las `<loc>` y `<image:loc>` generadas |

## Trampas conocidas
- Si `APP_URL` no apunta al dominio real de producción al ejecutar el comando desde CLI/cron, todas las URLs del sitemap se generarán con `http://localhost`.

## Tests que lo cubren
- `tests/Feature/SitemapCommandTest.php` (XML válido, exclusión de páginas inactivas, exclusión de colecciones sin imágenes o sin título, verificación de programación a las `03:42`).

## Pendiente real
- Ninguno verificado en código.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
