#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL ON RENDER"
echo "=========================================="

# 1. Force the script to import the container's live dashboard environment variables
DB_CONNECTION="pgsql"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

# 2. Safely pass them to Apache
echo "export DB_CONNECTION=pgsql" >> /etc/apache2/envvars
echo "export DB_HOST=$DB_HOST" >> /etc/apache2/envvars
echo "export DB_PORT=$DB_PORT" >> /etc/apache2/envvars
echo "export DB_DATABASE=$DB_DATABASE" >> /etc/apache2/envvars
echo "export DB_USERNAME=$DB_USERNAME" >> /etc/apache2/envvars
echo "export DB_PASSWORD=$DB_PASSWORD" >> /etc/apache2/envvars
echo "export APP_KEY=$APP_KEY" >> /etc/apache2/envvars
echo "export APP_ENV=production" >> /etc/apache2/envvars
echo "export APP_DEBUG=false" >> /etc/apache2/envvars

# 3. Explicitly inject the live dashboard variables straight into the migration command
echo "Running migrations against live database..."
DB_CONNECTION=pgsql \
DB_HOST="$DB_HOST" \
DB_PORT="$DB_PORT" \
DB_DATABASE="$DB_DATABASE" \
DB_USERNAME="$DB_USERNAME" \
DB_PASSWORD="$DB_PASSWORD" \
php artisan migrate --force

# 4. Start the web process
echo "Starting web server..."
exec apache2-foreground
