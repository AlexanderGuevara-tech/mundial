# Stage 1: Vendor dependencies
FROM php:8.3-fpm-alpine AS vendor

RUN set -eux; \
    apk add --no-cache \
        git \
        zip \
        unzip \
        libzip-dev \
        curl \
        oniguruma-dev \
        libpng-dev \
        libxml2-dev \
    ; \
    docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        zip \
        gd \
        xml \
        bcmath \
    ;

# Copy composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader

# Stage 2: Frontend assets
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json pnpm-lock.yaml ./
RUN corepack enable && pnpm install --frozen-lockfile

COPY resources/ ./resources/
RUN pnpm run build

# Stage 3: Runtime image
FROM php:8.3-fpm-alpine

RUN set -eux; \
    apk add --no-cache \
        supervisor \
        nginx \
        git \
        zip \
        unzip \
        libzip-dev \
        curl \
        oniguruma-dev \
        libpng-dev \
        libxml2-dev \
    ; \
    docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        zip \
        gd \
        xml \
        bcmath \
    ;

WORKDIR /var/www/html

# Copy vendor and built assets from previous stages
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Copy application source
COPY . .

# Remove dev-only files from runtime
RUN rm -rf node_modules resources/js resources/css

# Laravel optimizations
RUN php artisan optimize || true; \
    php artisan storage:link || true;

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Supervisor config
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
