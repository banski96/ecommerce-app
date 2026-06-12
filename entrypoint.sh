#!/bin/sh
set -e

# 1. Write the environment variables for Apache
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

# 2. Force Postgres variables inline so the shell layer cannot drop them
DB_CONNECTION=pgsql \
DB_HOST="$DB_HOST" \
DB_PORT="$DB_PORT" \
DB_DATABASE="$DB_DATABASE" \
DB_USERNAME="$DB_USERNAME" \
DB_PASSWORD="$DB_PASSWORD" \
php artisan migrate --force

# 3. Start Apache
exec apache2-foreground
