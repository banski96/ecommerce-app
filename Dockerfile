FROM php:8.3-apache

# ---------------------------------------
# System dependencies
# ---------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql gd

# ---------------------------------------
# Enable Apache rewrite (Laravel routes)
# ---------------------------------------
RUN a2enmod rewrite

# ---------------------------------------
# Set Laravel public folder as web root
# ---------------------------------------
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# ---------------------------------------
# Copy application code
# ---------------------------------------
COPY . /var/www/html

WORKDIR /var/www/html

# ---------------------------------------
# Install Composer
# ---------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---------------------------------------
# Install PHP dependencies
# ---------------------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ---------------------------------------
# Install Node dependencies + build assets
# ---------------------------------------
RUN npm install && npm run build

# ---------------------------------------
# Fix Laravel permissions
# ---------------------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# ---------------------------------------
# Laravel production optimization
# ---------------------------------------
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# ---------------------------------------
# Expose port for Render
# ---------------------------------------
EXPOSE 80
