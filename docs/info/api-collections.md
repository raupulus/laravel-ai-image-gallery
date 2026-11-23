# Módulo: `api-collections` (API REST v1 de ingesta)

## Qué hace y qué NO hace
- **Hace:**
  - Expone la API REST bajo `/api/v1` protegida con Laravel Sanctum (`auth:sanctum`) para que clientes externos (generadores de imágenes y publicadores de vídeo) creen colecciones, suban imágenes en base64 y asocien la URL de YouTube.
  - Convierte y redimensiona cada imagen subida en base64 a formato `.webp` (máximo 3840px de ancho, calidad 90) y genera su miniatura `_thumbnail.webp` (máximo 400px de ancho, calidad 70) mediante `Intervention\Image\Laravel\Facades\Image`.
  - Crea automáticamente `CollectionRole` por `slug` si no existe al recibir `role`.
  - Almacena temporalmente el array `seeds` en caché durante 3600 segundos para asociar cada `seed` al subir las imágenes por `order`.
- **NO hace:**
  - No expone endpoints públicos de lectura de colecciones (la lectura pública es web/AJAX en [`collections.md`](./collections.md)).
  - No llama a APIs externas de IA ni de YouTube; recibe los datos ya generados por herramientas satélite.

## Modelo de datos
- Opera sobre `collections`, `images` y `collection_roles` (ver detalle de esquema en [`collections.md`](./collections.md)).
- Usa `personal_access_tokens` (Sanctum) para autenticación por Bearer token.

## Flujos principales
1. **Crear o actualizar colección (`POST /api/v1/collections/add`):**
   - `CollectionAddRequest::prepareForValidation()` calcula `size_resized` (x4 si `resizer === "Real-ESRGAN x4"`), resuelve o crea `CollectionRole` por `role`, convierte `tags` array a CSV, normaliza `restore_faces` a booleano y trunca `title` (255) y `description` (1024).
   - `Collection::updateOrCreate(['batch_id' => ...], $validated)` crea/actualiza la colección.
   - Guarda `$seeds` en caché bajo la clave `collection-image-update-{collection_id}` durante 3600s.
   - Devuelve JSON `{ success: true, data: { collection_id, url } }`.
2. **Subir imagen a colección (`POST /api/v1/collections/update/{collection}`):**
   - `CollectionUpdateRequest::prepareForValidation()` recupera `seed` desde caché usando `order` como índice y añade `collection_id`.
   - Crea el registro `Image` y ejecuta `$image->storeImageFromBase64($imageBase64)`.
   - `storeImageFromBase64()` decodifica el base64 a un archivo temporal (`tmpfile()`), lo procesa con `InterventionImage::decodePath()`, guarda `storage/app/public/collections/{id}/{id}_{random30}.webp` y `_thumbnail.webp`, actualiza las rutas en BD y registra el cierre del temporal en `app()->terminating()`.
   - Devuelve JSON `{ success: true, request_data_validated, data: { image: { id, url_thumbnail, url_image } } }`.
3. **Asociar vídeo de YouTube (`POST /api/v1/collections/video/update`):**
   - `CollectionVideoUpdateRequest` valida `batch_id` (debe existir en `collections`) y `url_youtube`.
   - Actualiza `url_youtube` en la colección por `batch_id` y devuelve `{ success: true }`.
4. **Fallback (`routes/api.php`):**
   - Cualquier ruta inexistente bajo `/api/v1/*` devuelve `404` JSON `{"message": "Page Not Found"}`.

## Puntos de entrada
| Método | Ruta | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- |
| `GET` | `/api/v1/user` | `auth:sanctum` | Rate limit `api`: 60 req/min por usuario o IP |
| `POST` | `/api/v1/collections/add` | `auth:sanctum` (`!auth()->guest()`) | Rate limit `api`: 60 req/min |
| `POST` | `/api/v1/collections/update/{collection}` | `auth:sanctum` (`!auth()->guest()`) | Recibe `image` (base64) y `order` (int) |
| `POST` | `/api/v1/collections/video/update` | `auth:sanctum` (`!auth()->guest()`) | Actualiza `url_youtube` por `batch_id` |
| `ANY` | `/api/v1/*` (fallback) | Pública | Devuelve `404` JSON `{"message": "Page Not Found"}` |

## Dependencias
- **Depende de:** `laravel/sanctum`, `intervention/image-laravel` (con GD/Imagick y soporte WebP), `Cache` (`collection-image-update-{id}`), `storage/app/public/collections/`.
- **Es usado por:** clientes externos (herramientas satélite de generación de imágenes y publicación de vídeos).

## Configuración
| Variable / Clave | Valor por defecto | Efecto |
| --- | --- | --- |
| `CACHE_DRIVER` | `file` (`array` en tests) | Almacena temporalmente `seeds` entre `/add` y `/update/{collection}` (TTL 3600s) |
| `RouteServiceProvider` prefijo | `api/v1` | Prefijo global de `routes/api.php` y rate limit de 60 req/min |

## Trampas conocidas
- El prefijo real de las rutas API es `/api/v1` (configurado en `app/Providers/RouteServiceProvider.php`), no `/api`.
- `CollectionAddRequest::prepareForValidation()` trunca `title` a 255 caracteres con `Str::limit` aunque la columna y la regla `max:511` permiten 511.
- Si `Image::storeImageFromBase64()` falla, el bloque `try/catch` en `Api\CollectionController::update` está comentado, por lo que podría quedar un registro `Image` huérfano en BD sin archivos.
- En `CollectionUpdateRequest`, la regla de `image` es solo `'required'` (no valida MIME ni tamaño en la request).

## Tests que lo cubren
- `tests/Feature/ApiCollectionTest.php` (rechazo 401 sin auth, `/api/v1/user`, `/collections/add`, `/collections/update/{collection}` con PNG base64 1x1 y limpieza de archivo, `/collections/video/update`, fallback 404).

## Pendiente real
- `TODO` en `Image::storeImageFromBase64` (línea 120): comprobar si el archivo existe antes de guardar.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
