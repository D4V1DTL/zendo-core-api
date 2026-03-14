#!/bin/sh
set -e

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Checking if seed is needed..."
MODULE_COUNT=$(php artisan tinker --no-interaction --execute="echo \App\Models\PlatformModule::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')

if [ "$MODULE_COUNT" = "0" ] || [ -z "$MODULE_COUNT" ]; then
    echo "==> Seeding database (first deploy)..."
    php artisan db:seed --class=RolesAndAdminSeeder --force
    php artisan db:seed --class=PlatformModuleSeeder --force
    php artisan db:seed --class=BusinessPresetSeeder --force
    echo "==> Seeding complete."
else
    echo "==> Database already seeded ($MODULE_COUNT modules found), skipping."
fi

echo "==> Optimizing application..."
php artisan optimize

echo "==> Caching Filament components..."
php artisan filament:cache-components

echo "==> Starting FrankenPHP on port ${PORT:-8000}..."
exec frankenphp php-server --root /app/public --listen :${PORT:-8000}
