FROM php:8.1-fpm-buster

ARG UID=1000

WORKDIR /var/www/symfony

RUN apt-get update -y \
    && apt-get install -y git \
    zip \
    unzip \
    nano \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    freetype2-demos \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && usermod -u ${UID} www-data \
    && chown ${UID}:${UID} . \
    && chown ${UID}:${UID} /var/www \