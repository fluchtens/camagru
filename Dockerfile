FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        libpng-dev

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
