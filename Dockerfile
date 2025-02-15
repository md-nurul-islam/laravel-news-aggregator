FROM php:8.2-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    build-base \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Clear laravel cache
# RUN php artisan config:cache
# RUN php artisan view:cache
# RUN php artisan view:clear

# Generate application key
# RUN php artisan key:generate --ansi

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start install dependencies PHP-FPM and NGINX
CMD ["sh", "-c", "composer install \
    && composer run-script post-create-project-cmd \
    && php-fpm & nginx -g 'daemon off;' \
    "]
