#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL ON RENDER FREE TIER"
echo "=========================================="

# Force Laravel to wipe out any empty config caches built during docker compilation
# This forces the framework to read fresh environment variables directly from Render's dashboard
echo "Clearing application caches..."
php artisan config:clear
php artisan cache:clear

# Run database migrations automatically against Supabase
echo "Running migrations against live database..."
php artisan migrate --force

# Start the main Apache process (keeps container running)
echo "Starting Apache..."
exec apache2-foreground
