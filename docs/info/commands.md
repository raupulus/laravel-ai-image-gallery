# Comandos y scripts (`commands.md`)

## Desarrollo local

```bash
# Dependencias PHP y JS
composer install
pnpm install

# Assets frontend
pnpm dev          # Servidor Vite en desarrollo
pnpm build        # Compilación de producción

# Entorno y almacenamiento
cp .env.example .env
php artisan key:generate
php artisan storage:link

# Base de datos (PostgreSQL)
php artisan migrate
php artisan db:seed
```

## Tests y calidad de código

```bash
# Suite completa de PHPUnit
php artisan test

# Formateador de código (Laravel Pint)
./vendor/bin/pint
```

## Comandos Artisan propios y scheduler

| Comando | Descripción | Programación |
| --- | --- | --- |
| `php artisan sitemap:generate` | Genera `public/sitemap.xml` con portada, páginas activas y colecciones con imágenes | Diario a las `03:42` (`app/Console/Kernel.php`) |
| `php artisan sitemap:generate --path=/ruta/sitemap.xml` | Genera el sitemap en una ruta personalizada | Manual / tests |
| `php artisan sitemap:generate --force` | Acepta `--force` para compatibilidad CI/CD | Manual / CI |
| `php artisan schedule:list` | Lista tareas programadas registradas | Manual |
| `php artisan schedule:run` | Ejecuta el scheduler de Laravel (invocado por cron cada minuto) | Cron producción |

## Scripts bash (`scripts/`)

| Script | Uso | Notas |
| --- | --- | --- |
| `scripts/generate_env.sh [DB_USERNAME] [DB_PASSWORD]` | Crea o actualiza `.env` a partir de `.env.example.production` y variables de entorno (`APP_*`, `DB_*`, `GOOGLE_CAPTCHA_*`, `MAIL_*`) | Debe ejecutarse desde la raíz del proyecto y **no** como `root`. Usa `scripts/functions.sh`. |
| `scripts/exist_db_or_create.sh [DB_USERNAME] [DB_PASSWORD]` | Comprueba si existe la BD PostgreSQL `aidyslexic_raupulus_dev` y la crea si no existe | Requiere `psql`. |
| `scripts/webhooks/api-deploy.sh` | Despliegue automático: `artisan down`, `git checkout -- .`, `git pull`, `pnpm install && pnpm run build`, `composer install --no-dev`, `artisan migrate --force`, `artisan up` | Escribe logs en `storage/logs/script-laravel-ai-image-gallery-deploy.log`. |
| `scripts/functions.sh` | Funciones auxiliares compartidas (`replace_or_add_var_in_file`, etc.) | Cargado mediante `source` por otros scripts. |

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
