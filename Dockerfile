FROM php:8.2-fpm-alpine

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

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j4 \
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

WORKDIR /taqeem-app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /taqeem-app \
    && chmod -R 775 /taqeem-app/storage /taqeem-app/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
