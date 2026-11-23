#!/usr/bin/env bash
set -euo pipefail

## Crea el directorio de logs si no existiera.
mkdir -p 'storage/logs/webhooks'

## Crea el archivo sobre el que escribir los logs si no existiera.
if [[ ! -f 'storage/logs/webhooks/laravel-ai-image-gallery-deploy.log' ]]; then
    touch 'storage/logs/webhooks/laravel-ai-image-gallery-deploy.log'
    chmod 664 'storage/logs/webhooks/laravel-ai-image-gallery-deploy.log'
fi

LOG="storage/logs/script-laravel-ai-image-gallery-deploy.log"

cleanup() {
    php artisan up >> "$LOG" 2>> "$LOG" || true
}
trap cleanup EXIT

echo "Script para desplegar API comenzado correctamente" >> "$LOG" 2>> "$LOG"

## Pongo en modo mantenimiento.
php artisan down >> "$LOG" 2>> "$LOG"

## Descarto cambios realizados sobre archivos trackeados.
git checkout -- . >> "$LOG" 2>> "$LOG"

## Actualizo cambios.
git pull >> "$LOG" 2>> "$LOG"

## Actualizo paquetes de composer.
export COMPOSER_HOME=/tmp/composer_home
composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist >> "$LOG" 2>> "$LOG"

## Fuerzo actualización de migraciones nuevas y regenero cachés.
php artisan migrate --force >> "$LOG" 2>> "$LOG"
php artisan config:cache >> "$LOG" 2>> "$LOG"
php artisan route:cache >> "$LOG" 2>> "$LOG"
php artisan view:cache >> "$LOG" 2>> "$LOG"

echo "Script termina" >> "$LOG" 2>> "$LOG"
