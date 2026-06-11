#!/bin/sh

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
