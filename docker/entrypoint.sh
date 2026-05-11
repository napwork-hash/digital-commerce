#!/bin/sh
set -e

echo "[entrypoint] Bootstrapping Laravel..."

chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[entrypoint] Bootstrap complete."

exec "$@"