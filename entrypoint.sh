#!/bin/sh
set -e

echo "=========================================="
echo "GENERATING NATIVE ENVIRONMENT VIA TEMPLATE"
echo "=========================================="

# Create a template file directly inside the container shell memory
cat << 'EOF' > /var/www/html/.env.template
APP_ENV=production
APP_DEBUG=false
APP_KEY=$APP_KEY
DB_CONNECTION=pgsql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD
SESSION_DRIVER=cookie
CACHE_STORE=file
EOF

# Use envsubst to cleanly map dashboard values over to the production .env file
envsubst < /var/www/html/.env.template > /var/www/html/.env

echo "Successfully mapped environmental credentials securely."

# Run database migrations automatically
echo "Running migrations against live database..."
php artisan migrate --force

# Start the main Apache process
echo "Starting Apache..."
exec apache2-foreground
