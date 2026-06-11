#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL ON RENDER FREE TIER"
echo "=========================================="

# 1. Pipe the variables into Apache's environment file for web browser traffic
echo "export DB_CONNECTION=pgsql" >> /etc/apache2/envvars
echo "export DB_HOST=\$DB_HOST" >> /etc/apache2/envvars
echo "export DB_PORT=\$DB_PORT" >> /etc/apache2/envvars
echo "export DB_DATABASE=\$DB_DATABASE" >> /etc/apache2/envvars
echo "export DB_USERNAME=\$DB_USERNAME" >> /etc/apache2/envvars
echo "export DB_PASSWORD=\$DB_PASSWORD" >> /etc/apache2/envvars
echo "export APP_KEY=\$APP_KEY" >> /etc/apache2/envvars
echo "export APP_ENV=production" >> /etc/apache2/envvars
echo "export APP_DEBUG=false" >> /etc/apache2/envvars

# 2. Run migrations by explicitly passing Render's live variables inline
echo "Running migrations against live database..."
DB_CONNECTION=pgsql \
DB_HOST="$DB_HOST" \
DB_PORT="$DB_PORT" \
DB_DATABASE="$DB_DATABASE" \
DB_USERNAME="$DB_USERNAME" \
DB_PASSWORD="$DB_PASSWORD" \
php artisan migrate --database=pgsql --force

# 3. Start the foreground web process cleanly
echo "Starting web server..."
exec apache2-foreground
