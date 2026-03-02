FROM php:8.5-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev libjpeg-dev libonig-dev

RUN apt-get update \
    && apt-get install -y \
        apt-utils \
    && apt-get install -y \
        procps \
    && apt-get install -y \
        default-mysql-client \
        libzip-dev \
        libxml2-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) soap
