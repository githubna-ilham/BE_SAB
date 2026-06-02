FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        libpq-dev libzip-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && php artisan config:cache \
    && php artisan route:cache

ENV PORT=8080
EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=$PORT
