# ============================================================
# Stage 1: Frontend Assets
# ============================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --prefer-offline

COPY vite.config.js ./
COPY resources ./resources

RUN npm run build


# ============================================================
# Stage 2: Composer Dependencies
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files DULU sebelum source — layer cache lebih efisien
# composer install tidak re-run kalau composer.json/lock tidak berubah
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

# Copy source setelah install
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY public ./public
COPY resources ./resources
COPY routes ./routes
COPY storage ./storage
COPY artisan ./


# ============================================================
# Stage 3: Production Image
# ============================================================
FROM php:8.3-apache AS production

WORKDIR /var/www/html

# System deps + PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        libonig-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && a2enmod rewrite headers remoteip \
    && rm -rf /var/lib/apt/lists/*

# Opcache tuning — validate_timestamps=0 karena image immutable
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Apache vhost config
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy hasil build dari stage sebelumnya
COPY --from=vendor /app ./
COPY --from=assets /app/public/build ./public/build

# Entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permissions — www-data sebagai owner
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]