FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        libpng-dev

RUN a2enmod rewrite

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd

COPY . /var/www/html