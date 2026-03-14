FROM dunglas/frankenphp:php8.3-bookworm

RUN install-php-extensions \
    pdo_mysql mbstring gd intl zip opcache

RUN apt-get update && apt-get install -y \
    git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --prefer-dist

COPY . .

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && chmod -R 777 storage bootstrap/cache

COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

EXPOSE 8000

CMD ["/app/start.sh"]