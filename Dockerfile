# Use the official PHP 8.2 image with necessary extensions
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node and build frontend
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && npm install && npm run build

# Generate Laravel key and set storage link
RUN php artisan key:generate && php artisan storage:link

# Expose port 8000 and start Laravel
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
