FROM dunglas/frankenphp:php8.3-bookworm

# Install required PHP extensions (including intl and zip for Filament/openspout)
RUN install-php-extensions \
    ctype curl dom fileinfo filter hash intl mbstring openssl pcre pdo pdo_mysql redis session tokenizer xml zip opcache

# Configure OPcache for production (no file changes in container)
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=256\n\
opcache.max_accelerated_files=20000\n\
opcache.validate_timestamps=0\n\
opcache.revalidate_freq=0" > /usr/local/etc/php/conf.d/opcache-prod.ini

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for layer caching
COPY composer.json composer.lock ./

RUN composer install --optimize-autoloader --no-scripts --no-interaction --no-dev

# Copy the rest of the app
COPY . .

# Setup storage directories and permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x /app/start.sh

# Expose port (Railway injects $PORT)
EXPOSE 8000

CMD ["/app/start.sh"]