# Módulo: `collections` (Galería pública y administración web)

## Qué hace y qué NO hace
- **Hace:**
  - Muestra la galería principal paginada con búsqueda por título o etiquetas (`/`).
  - Permite carga incremental por scroll mediante endpoint AJAX (`/collections/ajax/get/cards/{page?}/{search?}`).
  - Muestra el detalle de una colección con todas sus imágenes y metadatos (`/collections/show/{collection}`).
  - Redirige a una colección aleatoria (`/collections/random`).
  - Permite a usuarios administradores eliminar una colección completa (incluyendo archivos físicos de sus imágenes) o una imagen concreta.
- **NO hace:**
  - No crea ni edita colecciones ni sube imágenes desde interfaz web (la ingesta ocurre únicamente vía [`api-collections.md`](./api-collections.md)).
  - No tiene sistema de favoritos, likes ni categorías filtrables desde navbar (los enlaces de navbar están comentados).

## Modelo de datos
- **`collections`** (`App\Models\Collection`):
  - Campos principales: `id`, `collection_role_id` (FK nullable a `collection_roles.id`, `ON DELETE SET NULL`, indexado), `role` (string legacy nullable), `batch_id` (string `unique`), `ai`, `title` (511), `description` (1024), `tags` (255, CSV), `prompt` (text), `negative_prompt` (text), `model`, `size`, `size_resized`, `steps` (50), `cfg_scale` (50), `denoising_strength` (50), `restore_faces` (bool cast), `resizer`, `refiner_model`, `url_youtube`, `created_at` (indexado), timestamps.
  - Relaciones: `images()` (`hasMany(Image)`), `primaryImageRelation()` (`hasOne(Image)->ofMany(['order' => 'min', 'id' => 'min'])`), `collectionRole()` (`belongsTo(CollectionRole)`, con alias `roleTmp()`).
  - Accessors: `isHorizontal`, `isVertical`, `titleResume` (límite 50 chars), `tagsArray`, `primaryImage` (usa `primaryImageRelation` o `images` precargadas para evitar N+1), `thumbnailDimensions` (`width`/`height` escalados a ancho 400 para evitar CLS), `url`, `youtubeEmbedUrl`.
  - Consulta compartida: `Collection::getQueryCollection(?string $search)` filtra con `whereHas('images')` y `title` no vacío, precarga únicamente `primaryImageRelation` y `withCount('images')` (sin hidratar todas las imágenes en `/` ni AJAX), y aplica búsqueda `ILIKE` (PostgreSQL, acelerada con índices GIN `pg_trgm`) en `title` y `tags` con comodines escapados (`%`, `_`).
  - Prevención N+1: `Model::preventLazyLoading(! app()->isProduction())` activo en `AppServiceProvider::boot()`.
- **`images`** (`App\Models\Image`):
  - Campos: `id`, `collection_id` (FK a `collections.id`, `ON DELETE CASCADE`, índice compuesto `(collection_id, order)`), `order` (int cast), `seed` (int cast), `image` (ruta relativa en `storage/app/public`), `thumbnail` (ruta relativa en `storage/app/public`), timestamps.
  - Accessors: `urlImage` (`asset('storage/' . $this->image)`), `urlThumbnail` (`asset('storage/' . $this->thumbnail)`).
  - Borrado seguro: `Image::safeDelete()` elimina los archivos físicos en `storage/app/public/` si existen y luego borra el registro.
- **`collection_roles`** (`App\Models\CollectionRole`):
  - Campos: `id`, `slug` (`unique`), `name`, `description`.
  - Seeder inicial: `Database\Seeders\CollectionRoleSeeder` (5 roles: `photographer_people`, `photographer_animals`, `photographer`, `artist_monster`, `artist`).

## Flujos principales
1. **Home y búsqueda (`IndexController@index`):**
   - Recibe `search` por query string, consulta `Collection::getQueryCollection($search)->orderByDesc('created_at')->orderByDesc('id')->paginate(24)` y renderiza `resources/views/home.blade.php` (marcando las 4 primeras tarjetas como eager y las 2 primeras con `fetchpriority="high"`).
2. **Scroll infinito AJAX (`CollectionController@ajaxGetHomeCards`):**
   - Un `IntersectionObserver` sobre `#box-collections-end` solicita `/collections/ajax/get/cards/{2 + currentPage}/{search?}` únicamente cuando `$collections->hasMorePages()` es verdadero y evitando peticiones solapadas (`isLoading`).
   - El controlador pagina de 8 en 8 con desempate `orderByDesc('id')`, renderiza `components._collection_block` por cada colección y devuelve JSON (`success`, `html`, `total`, `page`, `hasMorePages`, `lastItem`).
3. **Detalle y aleatoria (`CollectionController@show` / `random`):**
   - `show` precarga `$collection->load(['images', 'collectionRole'])` ordenadas por `(order, id)`, prioriza la imagen principal con `fetchpriority="high"` y dimensiones explícitas `width`/`height`, y aplica `loading="lazy"` + Fancybox `defer` en las miniaturas de `resources/views/collections/show.blade.php`.
   - `random` cachea los IDs válidos con imágenes en `collections.valid_ids` (TTL 10 min, invalidado al añadir imagen vía API o borrar colección/imagen) y elige uno con `Arr::random()` evitando `ORDER BY RANDOM()`, redirigiendo a `collections.show` (o a `home` si no hay ninguna).
4. **Borrado admin (`CollectionController@delete` / `deleteImage`):**
   - Requiere sesión autenticada (`auth`) y permiso `CollectionPolicy::delete` (`$user->isAdmin`; si no, devuelve `404`).
   - `delete` borra las imágenes y la colección dentro de `DB::transaction()` y, tras confirmar la transacción, elimina los archivos físicos y el directorio `collections/{id}` mediante `ImageStorageService`.
   - `deleteImage` verifica que `$image->collection_id === $collection->id`, borra el registro dentro de `DB::transaction()`, elimina los archivos físicos y devuelve JSON si `$request->isJson()` o redirige a `$collection->url`.
   - En interfaz (`collections/show.blade.php`), las confirmaciones de borrado emplean un `<dialog>` modal nativo con `aria-live` (`#admin-feedback-toast`) en lugar de `confirm()`/`alert()`.

## Puntos de entrada
| Método | Ruta | Nombre | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- | --- |
| `GET` | `/` | `home` | Pública | Pagina 24 colecciones con imágenes y título |
| `GET` | `/collections/show/{collection}` | `collections.show` | Pública | Route model binding por `id` |
| `GET` | `/collections/random` | `collections.random` | Pública | Redirige a `home` si no hay colecciones |
| `GET` | `/collections/ajax/get/cards/{page?}/{search?}` | `collection.ajax.get.cards` | Pública (`throttle:60,1`, `page` numérico `1..10000`) | Devuelve JSON con HTML renderizado (8 por página) y búsqueda escapada (`%`, `_`) |
| `POST` | `/collections/delete/{collection}` | `collection.delete` | `auth` + `isAdmin` | `404` si no es admin |
| `POST` | `/collections/{collection}/image/{image}/delete` | `collection.image.delete` | `auth` + `isAdmin` | `404` si no es admin o la imagen no pertenece a la colección |

## Dependencias
- **Depende de:** `auth` (`User::isAdmin`), `storage/app/public` (symlink `public/storage`), PostgreSQL (`ILIKE`).
- **Es usado por:** `sitemap` (`GenerateSitemap` consulta `Collection` e `Image`) y `api-collections` (crea los registros que este módulo muestra).

## Configuración
| Variable / Clave | Valor por defecto | Efecto |
| --- | --- | --- |
| `FILESYSTEM_DISK` / symlink `public/storage` | `local` + `php artisan storage:link` | Permite servir imágenes almacenadas en `storage/app/public/collections/{id}` |

## Trampas conocidas
- `Collection::getQueryCollection()` usa `ILIKE`, específico de PostgreSQL; no funcionará igual en SQLite/MySQL sin adaptar.
- El cálculo de página del scroll infinito en `home.blade.php` suma `2 + currentPage` para compensar que la carga inicial muestra 24 elementos y cada llamada AJAX devuelve 8.
- Las acciones admin devuelven `404` (no `403`) cuando el usuario autenticado no es administrador.

## Tests que lo cubren
- `tests/Feature/CollectionTest.php` (home, show, random, borrado colección/imagen admin y no-admin, endpoint AJAX).

## Pendiente real
- `TODO` en `CollectionController::show` (línea 26): crear sistema de notificaciones y/o búsqueda para controlar accesos a recursos inexistentes.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
