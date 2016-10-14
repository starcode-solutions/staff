FROM php:7.0-fpm

RUN apt-get update \
 && apt-get -y -qq --no-install-recommends install \
        ca-certificates \
        curl \
        git \
        libpq-dev \
        libzip-dev \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install \
        xdebug \
 && docker-php-ext-enable \
        xdebug \
 && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        zip

WORKDIR "/usr/local/etc/php/conf.d/"
RUN echo "xdebug.remote_enable = 1" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_autostart = 1" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_host = `/sbin/ip route|awk '/default/ { print $3 }'`" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.idekey = PHPSTORM" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_port = 9000" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_handler = dbgp" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_mode = req" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_connect_back = 1" >> docker-php-ext-xdebug.ini

WORKDIR "/var/www/html"