FROM php:8.0-fpm-alpine AS php

COPY php.ini /usr/local/etc/php

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install exif

RUN install -o www-data -g www-data -d /var/www/public/image