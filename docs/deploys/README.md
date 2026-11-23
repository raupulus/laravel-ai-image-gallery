# Guía de despliegue (`docs/deploys/`)

## Requisitos del servidor
- **PHP:** `>= 8.4` con extensión `pdo_pgsql`, `XMLWriter` y driver GD o Imagick con soporte **WebP**.
- **Base de datos:** PostgreSQL (con extensión `pg_trgm` para índices trigrama en búsquedas `ILIKE`).
- **Gestores de paquetes:** Composer `>= 2.2`.

## 1. Preparación inicial de base de datos y entorno
1. Crear la base de datos PostgreSQL (manual o mediante `scripts/exist_db_or_create.sh`):
   ```bash
   sudo -u postgres createdb -O web -T template1 aidyslexic_raupulus_dev
   ```
2. Preparar `.env` desde `.env.example.production` (o con `scripts/generate_env.sh` desde la raíz, sin usar `root`):
   - Verificar `APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=warning`, `SESSION_SECURE_COOKIE=true` y `APP_URL` con el dominio real (necesario para `sitemap:generate` y enlaces absolutos).
   - Configurar `APP_DESCRIPTION`, credenciales de PostgreSQL (`DB_*`), correo (`MAIL_*`), `CORS_ALLOWED_ORIGINS` si hubiera clientes web externos y opcionalmente `GOOGLE_ANALYTICS`.
   - El middleware global `SecurityHeaders` emite automáticamente `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` y `Strict-Transport-Security` bajo HTTPS.
3. Crear el symlink de almacenamiento público si no existe:
   ```bash
   php artisan storage:link
   ```

## 2. Despliegue manual estándar
```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan sitemap:generate
```

## 3. Despliegue automatizado por webhook
El repositorio incluye `scripts/webhooks/api-deploy.sh` (`set -euo pipefail` y `trap cleanup EXIT` para garantizar `php artisan up` ante fallos), que ejecuta en orden:
1. `php artisan down`
2. `git checkout -- .` y `git pull`
3. `composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist`
4. `php artisan migrate --force`
5. `php artisan config:cache`, `php artisan route:cache` y `php artisan view:cache`
6. `php artisan up` (mediante `trap ... EXIT`)

Los logs se escriben en `storage/logs/script-laravel-ai-image-gallery-deploy.log`.

## 4. Configuración recomendada en Nginx y Cloudflare (P0)
- **Cloudflare (SSL/TLS → Edge Certificates → HSTS):** habilitar HSTS con `Max-Age` `>= 31536000` (empezar con `300` en pruebas; actualmente en producción llega `max-age=0`).
- **Nginx (caché de estáticos y no duplicar cabeceras con Laravel `SecurityHeaders`):**
  ```nginx
  # Estáticos servidos directamente por Nginx (no pasan por SecurityHeaders.php)
  location ~* \.(?:webp|png|jpe?g|ico|woff2)$ {
      expires 1y;
      add_header Cache-Control "public, max-age=31536000, immutable" always;
      add_header X-Content-Type-Options "nosniff" always;
      try_files $uri =404;
  }

  location ~* \.(?:css|js)$ {
      expires 30d;
      add_header Cache-Control "public, max-age=2592000" always;
      add_header X-Content-Type-Options "nosniff" always;
      try_files $uri =404;
  }

  # En el bloque server o location / evitar añadir X-Frame-Options,
  # X-Content-Type-Options o Referrer-Policy de forma global para no
  # duplicar las cabeceras que ya emite Laravel SecurityHeaders en HTML.
  ```

## 5. Cron de producción (Laravel Scheduler)
Para ejecutar `sitemap:generate` diariamente a las `03:42` (`app/Console/Kernel.php`), añadir al crontab del usuario web:
```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
