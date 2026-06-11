#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL ON RENDER FREE TIER"
echo "=========================================="

# Run database migrations automatically against Supabase
echo "Running migrations against live database..."
php artisan migrate --force

# Start the main Apache process
echo "Starting Apache..."
exec apache2-foreground
