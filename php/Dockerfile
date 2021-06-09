FROM php:7.4.20-apache

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
