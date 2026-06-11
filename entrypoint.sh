#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL ON RENDER FREE TIER"
echo "=========================================="

# Escape the dollar signs (\$ ) so they evaluate dynamically at runtime!
echo "export DB_CONNECTION=pgsql" >> /etc/apache2/envvars
echo "export DB_HOST=\$DB_HOST" >> /etc/apache2/envvars
echo "export DB_PORT=\$DB_PORT" >> /etc/apache2/envvars
echo "export DB_DATABASE=\$DB_DATABASE" >> /etc/apache2/envvars
echo "export DB_USERNAME=\$DB_USERNAME" >> /etc/apache2/envvars
echo "export DB_PASSWORD=\$DB_PASSWORD" >> /etc/apache2/envvars
echo "export APP_KEY=\$APP_KEY" >> /etc/apache2/envvars
echo "export APP_ENV=production" >> /etc/apache2/envvars
echo "export APP_DEBUG=false" >> /etc/apache2/envvars

# Explicitly pass the live environment variables straight into the migration execution command
echo "Running migrations against live database..."
php artisan migrate --database=pgsql --force

# Start the foreground web process cleanly
echo "Starting web server..."
exec apache2-foreground
