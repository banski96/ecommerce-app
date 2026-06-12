#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL PRODUCTION CONTAINER"
echo "=========================================="

# 1. Inject runtime dashboard variables into Apache's configuration layer (for web traffic)
echo "export DB_CONNECTION=pgsql" >> /etc/apache2/envvars
echo "export DB_HOST=$DB_HOST" >> /etc/apache2/envvars
echo "export DB_PORT=$DB_PORT" >> /etc/apache2/envvars
echo "export DB_DATABASE=$DB_DATABASE" >> /etc/apache2/envvars
echo "export DB_USERNAME=$DB_USERNAME" >> /etc/apache2/envvars
echo "export DB_PASSWORD=$DB_PASSWORD" >> /etc/apache2/envvars
echo "export APP_KEY=$APP_KEY" >> /etc/apache2/envvars
echo "export APP_ENV=production" >> /etc/apache2/envvars
echo "export APP_DEBUG=false" >> /etc/apache2/envvars

# 2. Force PHP 8.4 CLI to read the live variables by passing them directly inline
echo "Running database migrations..."
DB_CONNECTION=pgsql \
DB_HOST="$DB_HOST" \
DB_PORT="$DB_PORT" \
DB_DATABASE="$DB_DATABASE" \
DB_USERNAME="$DB_USERNAME" \
DB_PASSWORD="$DB_PASSWORD" \
APP_KEY="$APP_KEY" \
APP_ENV=production \
php artisan migrate --force

# 3. Start the foreground web server process cleanly
echo "Starting Apache web server..."
exec apache2-foreground
