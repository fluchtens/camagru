FROM php:8.3-fpm

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo_mysql gd

RUN echo "access.log = /dev/null" >> /usr/local/etc/php-fpm.d/www.conf
