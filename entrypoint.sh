#!/bin/sh
set -e

echo "=== DATABASE VARIABLES ==="
env | grep DB_

php artisan config:clear
php artisan cache:clear

php artisan migrate --force

exec apache2-foreground
