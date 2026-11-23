# Decisiones técnicas (`decisiones-tecnicas.md`)

Registro de decisiones deliberadas del proyecto verificadas en el código, para evitar «arreglos» accidentales que rompan contratos o comportamiento esperado.

## 1. Registro público y flujos de contraseña deshabilitados en web
- **Dónde:** `routes/auth.php`.
- **Decisión:** Solo están activas las rutas `login` y `logout`. El registro (`register`), recuperación de contraseña, verificación de email y cambio de contraseña permanecen comentados aunque existan los controladores y vistas de Breeze.
- **Motivo:** El sitio es una galería personal administrada por el creador; no se admiten altas públicas de usuarios.

## 2. Respuesta `404` en acciones de administración sin permisos
- **Dónde:** `app/Http/Controllers/CollectionController.php` (`delete`, `deleteImage`).
- **Decisión:** Si el usuario autenticado no cumple `$user->isAdmin`, se aborta con `404` en lugar de `403`.
- **Motivo:** Ocultar la existencia de endpoints de borrado a usuarios sin privilegios (comportamiento cubierto por `CollectionTest::test_non_admin_cannot_delete_collection`).

## 3. Prefijo `/api/v1` y autenticación con Sanctum para ingesta
- **Dónde:** `app/Providers/RouteServiceProvider.php`, `routes/api.php`.
- **Decisión:** La API se versiona bajo `/api/v1` con rate limit de 60 req/min y autenticación obligatoria `auth:sanctum`.
- **Motivo:** Mantener un contrato estable con las herramientas satélite externas que suben colecciones, imágenes y vídeos.

## 4. Almacenamiento dual en WebP (original + miniatura) desde base64
- **Dónde:** `app/Models/Image.php::storeImageFromBase64`.
- **Decisión:** Cada imagen recibida en base64 se convierte a `.webp` (máx. 3840px, calidad 90) y genera un `_thumbnail.webp` (máx. 400px, calidad 70) en `storage/app/public/collections/{id}`.
- **Motivo:** Reducir peso en el grid principal usando miniaturas mientras se conserva alta resolución para Fancybox y descarga.

## 5. Caché temporal de `seeds` entre `/add` y `/update/{collection}`
- **Dónde:** `app/Http/Controllers/Api/CollectionController.php::add`, `app/Http/Requests/Api/CollectionUpdateRequest.php`.
- **Decisión:** Al crear la colección, el array `seeds` se guarda en caché (`collection-image-update-{id}`, TTL 3600s) para que las peticiones posteriores de subida de imágenes recuperen su `seed` por índice `order`.

## 6. Búsqueda case-insensitive con `ILIKE` de PostgreSQL
- **Dónde:** `app/Models/Collection.php::getQueryCollection`.
- **Decisión:** Se usa `ILIKE` sobre `title` y `tags`.
- **Implicación:** El proyecto asume PostgreSQL como motor de base de datos en desarrollo, tests y producción.

## 7. Páginas `cookies` y `rgpd` excluidas del sitemap mediante `active => false`
- **Dónde:** `app/Http/Controllers/PageController.php`, `app/Console/Commands/GenerateSitemap.php`.
- **Decisión:** `getActivePages()` filtra por `active === true` para que el sitemap solo indexe `about` y `social`, evitando indexar páginas legales aún vacías o inactivas.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
