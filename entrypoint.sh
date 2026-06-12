#!/bin/sh
set -e

echo "=== DATABASE VARIABLES ==="
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"

php artisan config:clear || true

php artisan migrate --force

exec apache2-foreground
