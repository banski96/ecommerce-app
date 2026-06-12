#!/bin/sh
set -e

echo "=========================================="
echo "BOOTING LARAVEL PRODUCTION CONTAINER"
echo "=========================================="

# 1. Write the configuration layer safely using a literal Here-Doc
# Wrapping EOF in single quotes prevents the shell from evaluating variables early
cat << 'EOF' >> /etc/apache2/envvars
export DB_CONNECTION=pgsql
export DB_HOST=$DB_HOST
export DB_PORT=$DB_PORT
export DB_DATABASE=$DB_DATABASE
export DB_USERNAME=$DB_USERNAME
export DB_PASSWORD=$DB_PASSWORD
export APP_KEY=$APP_KEY
export APP_ENV=production
export APP_DEBUG=false
EOF

# 2. Run migrations using the native global environment directly
echo "Running database migrations..."
php artisan migrate --force

# 3. Start the foreground web server process cleanly
echo "Starting Apache web server..."
exec apache2-foreground
