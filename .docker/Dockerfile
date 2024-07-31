FROM php:8.3-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev libjpeg-dev libonig-dev

RUN apt-get update \
    && apt-get install -y \
        apt-utils \
    && apt-get install -y \
        procps \
    && apt-get install -y \
        libpq-dev \
        libzip-dev \
        postgresql \
        postgresql-contrib \
        libxml2-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) soap
