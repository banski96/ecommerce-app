#!/bin/sh
set -e

echo "=========================================="
echo "FORCING LIVE ENVIRONMENT INJECTION"
echo "=========================================="

# Create a clean, production-ready .env file directly inside the container
cat <<EOF > /var/www/html/.env
APP_ENV=production
APP_DEBUG=false
APP_KEY=${APP_KEY}
DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
SESSION_DRIVER=cookie
CACHE_STORE=file
EOF

echo "Successfully injected Supabase credentials."

# Run database migrations automatically
echo "Running migrations..."
php artisan migrate --force

# Start the main Apache process
echo "Starting Apache..."
exec apache2-foreground
