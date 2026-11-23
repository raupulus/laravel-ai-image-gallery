# Guía de despliegue (`docs/deploys/`)

## Requisitos del servidor
- **PHP:** `>= 8.4` con extensión `pdo_pgsql`, `XMLWriter` y driver GD o Imagick con soporte **WebP**.
- **Base de datos:** PostgreSQL.
- **Gestores de paquetes:** Composer `>= 2.2` y `pnpm` `>= 10.x` / Node.js.

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
pnpm install --frozen-lockfile
pnpm build
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan sitemap:generate
```

## 3. Despliegue automatizado por webhook
El repositorio incluye `scripts/webhooks/api-deploy.sh`, que ejecuta en orden:
1. `php artisan down`
2. `git checkout -- .` y `git pull`
3. `pnpm install` y `pnpm run build`
4. `composer install --no-interaction --no-dev --prefer-dist`
5. `php artisan migrate --force`
6. `php artisan up`

Los logs se escriben en `storage/logs/script-laravel-ai-image-gallery-deploy.log`.

> ⚠️ Sin verificar en servidor real: el script `api-deploy.sh` no ejecuta `config:cache`, `route:cache`, `view:cache` ni `sitemap:generate`; si el servidor usa caché de configuración o rutas en producción, conviene refrescarlas tras el despliegue.

## 4. Cron de producción (Laravel Scheduler)
Para ejecutar `sitemap:generate` diariamente a las `03:42` (`app/Console/Kernel.php`), añadir al crontab del usuario web:
```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
