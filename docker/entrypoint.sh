#!/bin/sh
set -e

echo "[entrypoint] Bootstrapping Laravel..."

# Buat direktori framework jika belum ada (kasus PVC baru/kosong)
mkdir -p storage/framework/views \
         storage/framework/cache \
         storage/framework/sessions \
         storage/logs \
         bootstrap/cache

chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

php artisan config:cache
php artisan route:cache
# view:cache skip — di-compile on-demand ke storage/framework/views

echo "[entrypoint] Bootstrap complete."

exec "$@"