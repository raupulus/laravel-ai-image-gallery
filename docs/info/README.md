# Índice de documentación técnica (`docs/info/`)

Documentación técnica viva de **Laravel AI Image Gallery** (`AI Dyslexic`).
La fuente de verdad sobre el estado actual del proyecto sigue esta jerarquía: **código > `docs/info/` > `AGENTS.md` > el resto**.

## Módulos

| Módulo | Archivo | Responsabilidad |
| --- | --- | --- |
| Galería y colecciones web | [`collections.md`](./collections.md) | Home, búsqueda, detalle de colección, aleatoria, carga incremental AJAX y borrado admin |
| API REST v1 de colecciones | [`api-collections.md`](./api-collections.md) | Ingesta autenticada con Sanctum (`/api/v1/collections/*`), procesamiento WebP y asociación de vídeo |
| Páginas estáticas | [`pages.md`](./pages.md) | Páginas informativas (`/page/{page}`) y lista de proyectos/redes |
| Autenticación y roles | [`auth.md`](./auth.md) | Login/logout web, roles (`admin`), tokens Sanctum y rutas auxiliares |
| Sitemap y scheduler | [`sitemap.md`](./sitemap.md) | Comando `sitemap:generate` y tarea programada diaria a las `03:42` |

## Guías transversales

| Documento | Contenido |
| --- | --- |
| [`DESIGN.md`](./DESIGN.md) | Arquitectura visual, Bootstrap 5, tema claro/oscuro y hojas de estilo |
| [`COMPONENTS.md`](./COMPONENTS.md) | Layouts, vistas parciales, componentes Blade y Fancybox |
| [`commands.md`](./commands.md) | Comandos Artisan, Composer, pnpm, tests y scripts de `scripts/` |
| [`decisiones-tecnicas.md`](./decisiones-tecnicas.md) | Decisiones deliberadas de arquitectura y diseño |
| [`_MODULE_TEMPLATE.md`](./_MODULE_TEMPLATE.md) | Plantilla obligatoria para documentar módulos nuevos |

## Despliegue

- [`../deploys/README.md`](../deploys/README.md) — guía de despliegue, base de datos, variables de entorno y cron.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
