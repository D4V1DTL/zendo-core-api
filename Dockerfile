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

# copiar TODO el proyecto primero
COPY . .

RUN composer install --no-dev --optimize-autoloader --prefer-dist

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && chmod -R 777 storage bootstrap/cache

COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

EXPOSE 8000

CMD ["/app/start.sh"]