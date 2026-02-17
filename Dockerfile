# ============================================================
# Dockerfile: build the APP container only (Laravel + PHP-FPM)
# Use docker-compose to run app + DB + nginx together.
# ============================================================
FROM php:8.2-fpm-alpine

# System deps (including mariadb-dev for pdo_mysql)
RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    sqlite-dev \
    icu-dev \
    libxml2-dev \
    mariadb-dev \
    linux-headers

# PHP extensions: Laravel + DomPDF + SQLite + MySQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_sqlite \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    intl \
    gd \
    dom \
    xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
