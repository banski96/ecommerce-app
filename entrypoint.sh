#!/bin/sh

set -e

echo "===================="
echo "DATABASE DEBUG"
echo "===================="
echo "DB_CONNECTION=$DB_CONNECTION"
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"
echo "===================="

# Clear stale caches
php artisan config:clear || true
php artisan cache:clear || true

# Run database migrations automatically
echo "Running migrations..."
php artisan migrate --force

# Clear and optimize Laravel caches
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the main Apache process (keeps container running)
echo "Starting Apache..."
exec apache2-foreground
