FROM php:8.2-fpm as fp_php

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql mysqli \
    && pecl install apcu \
    && docker-php-ext-enable apcu mysqli \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/php