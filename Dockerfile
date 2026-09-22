FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    default-mysql-client \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
COPY .env.docker /var/www/html/.env

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build \
    && php artisan storage:link || true \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=8000
EXPOSE 8000

CMD ["sh", "-c", "php artisan key:generate --force || true && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
