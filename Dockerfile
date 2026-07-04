FROM php:8.3-fpm-alpine

# Install system dependencies (termasuk nginx)
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    unzip \
    oniguruma-dev \
    git \
    nodejs \
    npm \
    nginx

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install composer and node dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

# Salin konfigurasi Nginx Produksi
COPY nginx-prod.conf /etc/nginx/http.d/default.conf

# Salin entrypoint script & buat menjadi executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions untuk Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port HTTP default
EXPOSE 80

# Jalankan entrypoint script yang memulai PHP-FPM dan Nginx
CMD ["/usr/local/bin/docker-entrypoint.sh"]
