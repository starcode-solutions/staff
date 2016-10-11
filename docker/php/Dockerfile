FROM php:7.1-fpm

RUN apt-get update \
 && apt-get install -qy \
        libpq-dev

RUN docker-php-ext-install -j$(nproc) \
        pdo_pgsql
